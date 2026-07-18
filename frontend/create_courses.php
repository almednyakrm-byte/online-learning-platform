<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Define module slug
$mod_slug = 'courses';

// Define page title
$page_title = 'Create Course';

// Include header
include 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-blue-500">Create Course</h3>
                <p class="mt-1 text-sm text-gray-600">Fill in the details below to create a new course.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="create-course-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="course_name" id="course_name" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Course Name">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="course_description" class="block text-sm font-medium text-gray-700">Course Description</label>
                                <div class="mt-1">
                                    <textarea id="course_description" name="course_description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Course Description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="course_duration" class="block text-sm font-medium text-gray-700">Course Duration</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="course_duration" id="course_duration" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Course Duration">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="course_fee" class="block text-sm font-medium text-gray-700">Course Fee</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="course_fee" id="course_fee" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" placeholder="Course Fee">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-300 hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Create Course</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>