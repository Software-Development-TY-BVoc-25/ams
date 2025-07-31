<?php
session_start();
$conn = new mysqli("localhost", "root", "", "atten");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Replace this with actual session variable if login is implemented
$teacher_id = 10; // Example: Ms. Sneha Prabhudessai

// Fetch only classes assigned to the logged-in teacher
$classQuery = "
  SELECT DISTINCT c.Class_ID, CONCAT(c.Year_Level, ' ', c.Course_Code, ' ', c.Division) AS Class_Name
  FROM class c
  JOIN tr_subject_allocation tsa ON c.Class_ID = tsa.Class_ID
  WHERE tsa.Teacher_ID = $teacher_id
";
$classes = $conn->query($classQuery);

// Fetch all subjects for dropdown (you can limit this by teacher if needed)
$subjectQuery = "
  SELECT s.Subject_ID, s.Subject_Name 
  FROM subject s
  JOIN tr_subject_allocation tsa ON s.Subject_ID = tsa.Subject_ID
  WHERE tsa.Teacher_ID = $teacher_id
";
$subjects = $conn->query($subjectQuery);

// Fetch students (for now all; optional: filter by selected class with JS or in atten.php)
$students = $conn->query("SELECT * FROM student");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance Sheet</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="college-banner">
  <img src="cllglogo.png" alt="College Header" class="college-header">
</div>

<div class="attendance-form-container">
  <h2>Attendance Sheet</h2>
  <form method="POST" action="atten.php">

    <div class="form-group">
      <label for="date">Date:</label>
      <input type="date" name="date" required>

      <label for="class_id">Class:</label>
      <select name="class_id" required>
        <option value="">--Select Class--</option>
        <?php while ($cls = $classes->fetch_assoc()): ?>
          <option value="<?= $cls['Class_ID'] ?>"><?= $cls['Class_Name'] ?></option>
        <?php endwhile; ?>
      </select>

      <label for="subject">Subject:</label>
      <select name="subject_id" required>
        <option value="">--Select Subject--</option>
        <?php while ($sub = $subjects->fetch_assoc()): ?>
          <option value="<?= $sub['Subject_ID'] ?>"><?= $sub['Subject_Name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <table>
      <tr>
        <th>Sr No</th>
        <th>Name</th>
        <th>Roll No</th>
        <th>
          Present<br>
          <input type="checkbox" id="markAll" onclick="toggleAll(this)">
          <label for="markAll">Mark All</label>
        </th>
      </tr>

      <?php $i = 1; while ($row = $students->fetch_assoc()): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($row['Student_Name']) ?></td>
        <td><?= htmlspecialchars($row['Student_Rollno']) ?></td>
        <td>
          <input type="checkbox" name="present[]" value="<?= $row['Student_ID'] ?>" class="present-check">
          <input type="hidden" name="student_ids[]" value="<?= $row['Student_ID'] ?>">
        </td>
      </tr>
      <?php endwhile; ?>
    </table>

    <button type="submit">Submit Attendance</button>
  </form>
</div>

<script>
  function toggleAll(source) {
    document.querySelectorAll('.present-check').forEach(cb => cb.checked = source.checked);
  }
</script>

</body>
</html>
