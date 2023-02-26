<?php
session_start();
require_once '../classes/user.php';
require_once '../classes/department.php';
require_once '../classes/course.php';
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
  if ($type != 1) {
    header('Location: ../authentication/login');
  }
} else {
  header('Location: ../authentication/login');
}

$message2 = '';
if (isset($_POST['submit-department'])) {
  $department = $conn->real_escape_string($_POST['department']);
  if (Department::create($department)) {
    $message2 = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  }
}
if (isset($_POST['submit-course'])) {
  $departmentId = $conn->real_escape_string($_POST['department_id']);
  $course = $conn->real_escape_string($_POST['course']);
  if (Course::create($departmentId, $course)) {
    $message2 = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  }
}
if (isset($_POST['deleteRecord'])) {
  $id = $_POST['id'];
  if ($_POST['deleteRecord'] == 'department') {
    Department::destroy($id);
    $message2 = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Deleted</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  } else if ($_POST['deleteRecord'] == 'course') {
    Course::destroy($id);
    $message2 = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Deleted</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  }
}



if (isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  $type = 'all';
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once '../templates/header.php' ?>
  <title>PCLU - Admin | Alumni</title>
  <link rel="stylesheet" href="admin.css?">
  <style>
    .dash-card {
      transition: all 0.15s ease;
    }

    .dash-card:hover {
      border-color: #fff;
      box-shadow: 0 0rem 2rem rgba(0, 0, 0, .15) !important;
    }
  </style>
</head>

<body>
  <div id="a-wrapper">
    <?php include_once '../templates/admin_sidebar.php' ?>
    <div id="a-main">
      <?php include_once '../templates/admin_nav.php' ?>
      <div id="content">
        <div class="d-flex align-items-center justify-content-between">
          <div class="h5 fw-bold mb-0"><i class="fas fa-user-graduate me-2"></i>Alumni</div>
          <div class="text-end">
            <select form="filter-form" name="type" id="typeOpt" class="form-select float-end dash-card" onchange="fetchAlumni()">
              <option value="all" <?= ($type == 'all') ? 'selected' : '' ?>>All</option>
              <option value="registered" <?= ($type == 'registered') ? 'selected' : '' ?>>Registered</option>
              <option value="unverified" <?= ($type == 'unverified') ? 'selected' : '' ?>>Unverified</option>
            </select>
          </div>

        </div>

        <hr>
        <div class="row">
          <div class="col-md-3">
            <div class="card mb-3 dash-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="h6 fw-bold">Manage</div>
                  <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Add
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#depModal">Department</a></li>
                      <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#courseModal">Course</a></li>
                    </ul>
                  </div>

                </div>

                <hr>
                <?= $message2 ?>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Departments
                      </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body px-0">
                        <ul class="list-group list-group-flush">
                          <?php
                          $departmentsResult = Department::getAllDepartments();
                          if ($departmentsResult->num_rows > 0) {
                            while ($row = $departmentsResult->fetch_assoc()) {
                              echo '<li class="list-group-item smalltxt ">' . $row['description'] . '<i class="far fa-trash-alt float-end cursor text-danger" onclick="deleteRecord(\'department\',' . $row['id'] . ')"></i></li>';
                            }
                          }
                          ?>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingThree">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        Courses
                      </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body px-0">
                        <ul class="list-group list-group-flush">
                          <?php
                          $coursesResult = Course::getAllCourses();
                          if ($coursesResult->num_rows > 0) {
                            while ($row = $coursesResult->fetch_assoc()) {
                              echo '<li class="list-group-item smalltxt" >' . $row['description']  . ' (' . $row['count'] . ')<i class="far fa-trash-alt float-end cursor text-danger" onclick="deleteRecord(\'course\',' . $row['id'] . ')"></i></li>';
                            }
                          }
                          ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card mb-3 dash-card">
              <div class="card-body">
                <div class="h6 fw-bold">Filter Result</div>
                <form action="export-csv" method="post" id="filter-form">
                  <div class="smalltxt mb-1">
                    Department
                  </div>
                  <select name="department" id="depOpt" class="form-select mb-3" onchange="changeDept($(this).val())">
                    <option value="">All</option>
                    <?php
                    $departmentsResult = Department::getAllDepartments();
                    if ($departmentsResult->num_rows > 0) {
                      while ($row = $departmentsResult->fetch_assoc()) {
                    ?>
                        <option value="<?= $row['id'] ?>"><?= $row['description'] ?></option>
                    <?php                      }
                    }
                    ?>
                  </select>
                  <div class="smalltxt mb-1">
                    Course
                  </div>
                  <select name="course" id="crsOpt" class="form-select mb-3" onchange="fetchAlumni()">
                    <option value="">All</option>
                    <?php
                    $coursesResult = Course::getAllCourses();
                    if ($coursesResult->num_rows > 0) {
                      while ($row = $coursesResult->fetch_assoc()) {
                    ?>
                        <option value="<?= $row['id'] ?>" class="crs crs-dep-<?= $row['department_id'] ?>"><?= $row['description'] ?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                  <div class="smalltxt mb-1">
                    Batch
                  </div>
                  <select name="batch" id="batchOpt" class="form-select mb-3" onchange="fetchAlumni()">
                    <option value="">All</option>
                    <?php
                    $batchesResult = User::getBatches();
                    if ($batchesResult->num_rows > 0) {
                      while ($row = $batchesResult->fetch_assoc()) {
                    ?>
                        <option value="<?= $row['batch'] ?>"><?= $row['batch'] ?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                  <div class="smalltxt mb-1">
                    Employment Status
                  </div>
                  <select name="employment" id="empOpt" class="form-select mb-3" onchange="fetchAlumni()">
                    <option value="">All</option>

                    <option value="2" <?= ($type == "unemployed") ? 'selected' : '' ?>>Unemployed</option>
                    <option value="1" <?= ($type == "employed") ? 'selected' : '' ?>>Employed</option>
                    <!-- <option value="3">Self Employed</option> -->
                  </select>
                  <button type="submit" class="btn w-100 btn-success">Export to CSV</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="" id="tbl-res"></div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="depModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">New Department</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" id="depForm">
            <div class="h6">Department name</div>
            <input type="text" name="department" class="form-control" required>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="submit-department" form="depForm" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">New Course</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" id="courseForm">
            <div class="h6">Department</div>
            <select name="department_id" id="" class="form-select mb-3" required>
              <?php
              $departmentsResult1 = Department::getAllDepartments();
              if ($departmentsResult1->num_rows > 0) {
                while ($row = $departmentsResult1->fetch_assoc()) {
                  echo '<option value="' . $row['id'] . '">' . $row['description'] . '</option>';
                }
              }
              ?>
            </select>
            <div class="h6">Course Name</div>
            <input name="course" type="text" class="form-control" required>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
          <button type="submit" form="courseForm" name="submit-course" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>

  <?php include_once '../templates/footer.php' ?>
  <script>
    fetchAlumni();

    function fetchAlumni() {
      $("#tbl-res").load('component/alumni-table', {
        type: $("#typeOpt").val(),
        department: $("#depOpt").val(),
        course: $("#crsOpt").val(),
        batch: $("#batchOpt").val(),
        employment: $("#empOpt").val(),
      });
    }

    function deleteRecord(type, id) {
      // alert($type);
      Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $e = `<form action="" method="post" id="quickSubmit">
                    <input type="text" name="deleteRecord" value="${type}">
                    <input type="text" name="id" value="${id}">
                </form>`;
          $("body").append($e);
          $("#quickSubmit").submit();
        }
      })

    }

    function changeDept(val) {
      console.log(val);

      if (val != '') {
        $(".crs").addClass('d-none');
        $(".crs-dep-" + val).removeClass('d-none');
      } else {
        $(".crs").removeClass('d-none');
      }

      $("#crsOpt").val('');
      fetchAlumni();
    }
  </script>
</body>

</html>