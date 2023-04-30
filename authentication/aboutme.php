<?php
session_start();
require_once '../classes/auth.php';
require_once '../classes/user.php';
require_once '../classes/course.php';

$id = $_GET['id'];
$message = '';
$str1 = '';
$str2 = '';

if (isset($_POST['submit'])) {
    extract($_POST);

    if ($employment_date_current != '') {
        $str1 = "`employment_date_first` = '$employment_date_first',";
    }
    if ($employment_date_first != '') {
        $str2 = "`employment_date_current`='$employment_date_current',";
    }
    $sql = "UPDATE `users` SET `birth_date`='$birth_date',`civil_status`='$civil_status',`gender`='$gender',`address_line`='$address_line',`muncity`='$muncity',`province`='$province',`contact`='$contact',`course`='$course',`batch`='$batch',`student_id`='$student_id',`graduation_date`='$graduation_date',`employment_status`='$employment_status', $str1 $str2 `nature_of_work` = '$nature_of_work', `current_position`='$current_position' WHERE id = $id";
    // echo $sql;
    if ($conn->query($sql)) {
        session_destroy();

        header('Location: ../authentication/login?status=success');
    } else {
        echo $conn->error;
    }
}

$usersResult = $conn->query("SELECT * FROM users WHERE id = $id");
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();

    extract($user);
    if ($birth_date !== null) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Tell us more about you</title>
    <link rel="stylesheet" href="auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        #right-side {
            height: 100vh;
            overflow: auto;
        }

        /* 
        @media only screen and (max-width: 768px) {
            #right-side {
                height: auto !important;
            }
        } */
    </style>
</head>

<body>
    <div class="vh-100 d-flex flex-md-row flex-sm-column flex-column align-items-stretch justify-content-stretch">
        <div class=" w-75 login-left d-flex align-items-center justify-content-center">
            <div class="text-center">
                <img src="../assets/images/logo.png" height="200" alt="" id="logo" class="mb-md-4 md-sm-2 md-2">
                <div style="text-shadow: 2px 3px 9px rgba(0,0,0,0.45)">
                    <div class="display-2 fw-bolder text-white">ALMA-TECH</div>
                    <div class="display-6 text-white">Alumni Portal</div>
                </div>

            </div>
        </div>
        <div class=" w-100  px-md-5" id="right-side">
            <div class="p-3 login-form">
                <div id="login-title" class=" mt-md-5 mt-sm-0 mt-0 mb-5">
                    <div class="h5 fw-">Tell us more about you,</div>
                    <div class="h2 fw-bold"><?= $first_name . ' ' . $last_name ?></div>
                </div>
                <div class="h6 text-danger ">
                    <?= $message; ?>
                </div>
                <form action="" method="POST">
                    <div class="h6 mb-0">Personal Information</div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Birthdate</div>
                            <input type="date" class="form-control mb-3 border" id="birth_date" name="birth_date" value="<?= $birth_date ?>" required>
                        </div>
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Civil Status</div>
                            <select name="civil_status" id="" class="form-select mb-3">
                                <option value="1" <?= ($civil_status == 1) ? 'selected' : '' ?>>Single</option>
                                <option value="2" <?= ($civil_status == 2) ? 'selected' : '' ?>>Married</option>
                                <option value="3" <?= ($civil_status == 3) ? 'selected' : '' ?>>Annuled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Gender</div>
                            <select name="gender" id="" class="form-select mb-3">
                                <option value="1" <?= ($gender == 1) ? 'selected' : '' ?>>Male</option>
                                <option value="2" <?= ($gender == 2) ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="h6 mb-0">Contact Information</div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Contact #</div>
                            <input type="text" class="form-control mb-3 border" id="contact" name="contact" value="<?= $contact ?>" required>
                        </div>
                        <div class="col-md-8">
                            <div class="smalltxt mb-1 fw-bold">Region</div>
                            <!-- <input type="text" class="form-control mb-3 border" id="address_line" name="address_line" value="<?= $address_line ?>" required> -->
                            <select name="" id="region" class="form-select mb-3 border"></select>
                        </div>
                        <!-- <div class="col-md-8">
                            <div class="smalltxt mb-1 fw-bold">Barangay</div>
                            <input type="text" class="form-control mb-3 border" id="address_line" name="address_line" value="<?= $address_line ?>" required>
                            <select name="" id="region" class="form-select mb-3 border"></select>
                        </div> -->
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Province</div>
                            <input type="text" class="form-control mb-3 border d-none" id="province" name="province" value="<?= $province ?>" required>
                            <select name="" id="province1" class="form-select mb-3 border" required></select>
                        </div>
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Municipality</div>
                            <input type="text" class="form-control mb-3 border d-none" id="muncity" name="muncity" value="<?= $muncity ?>" required>
                            <select name="" id="city1" class="form-select mb-3 border" required></select>
                        </div>
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Barangay</div>
                            <input type="text" class="form-control mb-3 border d-none" id="address_line" name="address_line" value="<?= $muncity ?>" required>
                            <select name="" id="barangay1" class="form-select mb-3 border" required></select>
                        </div>
                        <!-- <div class="col-md-2">
                            <div class="smalltxt mb-1 fw-bold">Zip Code</div>
                            <input type="text" class="form-control mb-3 border" id="zip_code" pattern="[0-9]{4}" name="zip_code" value="<?= $zip_code ?>" placeholder="0000" required>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">

                                    <div class="h6 mb-0">Alumni Information</div>
                                    <hr>

                                    <div class="smalltxt mb-1 fw-bold">Student ID</div>
                                    <input type="text" class="form-control mb-3 border" id="student_id" name="student_id" value="<?= $student_id ?>" required>

                                    <div class="smalltxt mb-1 fw-bold">Course</div>
                                    <select name="course" id="" class="form-select mb-3" required>
                                        <option value="">- - -</option>
                                        <?php
                                        $coursesResult = Course::getAllCourses();
                                        if ($coursesResult->num_rows > 0) {
                                            while ($row = $coursesResult->fetch_assoc()) {
                                        ?>
                                                <option value="<?= $row['id'] ?>" <?= ($course == $row['id']) ? 'selected' : '' ?>><?= $row['description'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                    <div class="smalltxt mb-1 fw-bold">Batch</div>
                                    <select name="batch" id="" class="form-select border mb-3">
                                        <?php
                                        for ($current = date('Y'); $current >= 1980; $current--) {
                                            echo '<option value="' . $current . '">' . $current . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <!-- <input type="text" name="batch" class="form-control mb-3 border" pattern="[0-9]{4}" value="<?= $batch ?>" placeholder="ex. 2019"> -->

                                    <div class="smalltxt mb-1 fw-bold">Graduation Date</div>
                                    <input type="date" class="form-control mb-3 border" id="graduation_date" name="graduation_date" value="<?= $graduation_date ?>" required>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">

                                    <div class="h6 mb-0">Employment Information</div>
                                    <hr>
                                    <div class="smalltxt fw-bold mb-1">Status</div>
                                    <select name="employment_status" id="employment_status" value="" class="form-select mb-3" onchange="checkEmp($(this))">
                                        <option value="2" <?= ($employment_status == 2) ? 'selected' : '' ?>>Unemployed</option>
                                        <option value="1" <?= ($employment_status == 1) ? 'selected' : '' ?>>Employed</option>
                                        <!-- <option value="3" <?= ($employment_status == 3) ? 'selected' : '' ?>>Self Employed</option> -->
                                    </select>

                                    <div id="employed-form">
                                        <div class="smalltxt fw-bold mb-1">Employment Date (1st) <span class="fw-light">Optional</span></div>
                                        <input type="date" name="employment_date_first" class="form-control mb-3 emp-add" value="<?= $employment_date_first ?>">


                                        <div class="smalltxt fw-bold mb-1">Employment Date (Current) <span class="fw-light">Optional</span></div>
                                        <input name="employment_date_current" type="date" class="form-control mb-3 emp-add" value="<?= $employment_date_current ?>">


                                        <div class="smalltxt fw-bold mb-1">Nature of Work</div>
                                        <select name="nature_of_work" id="nature_of_work" class="form-select border mb-3 emp-add" required>
                                            <option value="">- - -</option>
                                            <?php

                                            $natures = array(
                                                'Car Dealership',
                                                'Casino/Gambling',
                                                'Construction and Engineering',
                                                'Education',
                                                'Farming, Fishing and Forestry',
                                                'Fashion and Entertainment',
                                                'Finance and Accounting',
                                                'Food and Retail',
                                                'Forex Trading and Money Changer',
                                                'General Labor',
                                                'Government Employee',
                                                'Healthcare and Medical Services',
                                                'Hospitality and Tourism',
                                                'Human Resource',
                                                'Insurance',
                                                'IT and Technical Services',
                                                'Jewelry Trading',
                                                'Legal Practice',
                                                'Management and Consultancy',
                                                'Manpower Services',
                                                'Manufacturing and Production',
                                                'Maritime Industry',
                                                'Media and Journalism',
                                                'Mining and Quarrying',
                                                'Multi-Level Marketing',
                                                'Non-Profits, Charity, and Social Work',
                                                'Pawnshop',
                                                'Personal, Wellness, Beautification, Leisure Services',
                                                'Pharmaceuticals',
                                                'Public Services and National Defense',
                                                'Real State',
                                                'Remittance',
                                                'Specialized Professionals',
                                                'Transportation and Logistics',
                                                'Trust Entities',
                                                'Utilities and Sanitation',
                                                'Wholesale and Retail'
                                            );

                                            foreach ($natures as $nature) {
                                                echo '<option value="' . $nature . '">' . $nature . '</option>';
                                            }
                                            ?>
                                        </select>


                                        <div class="smalltxt fw-bold mb-1">Current Position <span class="fw-light">Optional</span></div>
                                        <input name="current_position" type="text" class="form-control mb-3" value="<?= $current_position ?>" placeholder="ex. Business Manager">


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex">

                        <input class="form-check-input mt-0 me-2" type="checkbox" value="" aria-label="Checkbox for following text input" required>
                        <div class="smalltxt mb-1">By submitting this form, you have read and agree to our <span type="button" class="text-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Terms & Conditions</span>.</div>
                    </div>

                    <div class="text-end mt-2">
                        <button type="submit" name="submit" class="btn btn-lg text-white btn-primary shadow">Submit <i class="fas fa-arrow-right ms-3 mt-1"></i></button>
                    </div>

                </form>
            </div>
        </div>
    </div><!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Terms & Conditions</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ol>
                        <li>Acceptance of Terms: By accessing and using this alumni portal, you agree to be bound by these terms and conditions, as well as the provisions of Republic Act No. 10173 or the Data Privacy Act of 2012, and any other applicable laws and regulations.</li>
                        <li>Personal Information: To access certain features of the alumni portal, you may be required to provide personal information such as your name, contact details, educational background, and other relevant data. Your personal information will be collected, processed, and stored in accordance with the provisions of the Data Privacy Act.</li>
                        <li>Use of Personal Information: By providing your personal information on the alumni portal, you consent to its use for the purposes of maintaining your alumni records, providing you with updates and information about alumni events and activities, and for other related purposes.</li>
                        <li>Third-Party Sharing: We may share your personal information with other third-party service providers, such as payment processors and event organizers, for the purpose of facilitating alumni activities and events. We will not share your personal information with any other third parties without your prior consent, except as required by law.</li>
                        <li>Prohibited Conduct: You agree not to engage in any conduct that violates any applicable laws or regulations, infringes on the rights of any third party, or disrupts or harms the alumni portal or its users.</li>
                        <li>Termination: We may terminate your access to the alumni portal at any time for any reason, with or without notice.</li>
                        <li>Disclaimer of Warranties: We provide this alumni portal on an "as is" and "as available" basis, without any warranties or representations of any kind, whether express or implied.</li>
                        <li>Limitation of Liability: In no event shall we be liable for any damages, including without limitation, direct or indirect, special, incidental, or consequential damages, arising out of or in connection with your use of the alumni portal.</li>
                        <li>Indemnification: You agree to indemnify and hold us harmless from any claims, damages, or expenses arising out of your use of the alumni portal or any content you upload or submit to the portal.</li>
                        <li>Governing Law: These terms and conditions shall be governed by and construed in accordance with the laws of the Republic of the Philippines, without giving effect to any principles of conflicts of law.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.0.js"></script>
    <script>
        checkEmp($("#employment_status"));

        function checkEmp(emp) {
            empValue = emp.val();
            if (empValue == 1) {
                //show
                $('#employed-form').removeClass("d-none");
                $('.emp-add').val("");
                $('#nature_of_work').attr('required', true);
            } else {
                //hide
                $('#employed-form').addClass("d-none");
                $('#nature_of_work').attr('required', false);
            }
        }


        var my_handlers = {

            fill_provinces: function() {
                var region_code = $(this).val();
                $('#province1').ph_locations('fetch_list', [{
                    "region_code": region_code
                }]);
                var selectedOptionText = $(this).find('option:selected').text();
                console.log(selectedOptionText);

            },

            fill_cities: function() {

                var province_code = $(this).val();
                $('#city1').ph_locations('fetch_list', [{
                    "province_code": province_code
                }]);
                var selectedOptionText = $(this).find('option:selected').text();
                console.log(selectedOptionText);
                $("#province").val(selectedOptionText);
            },


            fill_barangays: function() {
                var city_code = $(this).val();
                $('#barangay1').ph_locations('fetch_list', [{
                    "city_code": city_code
                }]);
                var selectedOptionText = $(this).find('option:selected').text();
                console.log(selectedOptionText);
                $("#muncity").val(selectedOptionText);
            }
        };

        $(function() {
            $('#region').on('change', my_handlers.fill_provinces);
            $('#province1').on('change', my_handlers.fill_cities);
            $('#city1').on('change', my_handlers.fill_barangays);
            $('#barangay1').on('change', function() {
                var selectedOptionText = $(this).find('option:selected').text();
                console.log(selectedOptionText);
                $("#address_line").val(selectedOptionText);
            });
            $('#region').ph_locations({
                'location_type': 'regions'
            });
            $('#province1').ph_locations({
                'location_type': 'provinces'
            });
            $('#city1').ph_locations({
                'location_type': 'cities'
            });
            $('#barangay1').ph_locations({
                'location_type': 'barangays'
            });

            $('#region').ph_locations('fetch_list');
        });
    </script>

</body>

</html>