<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="background-shapes">
        <div class="shape1"></div>
        <div class="shape2"></div>
        <div class="shape3"></div>
        <div class="shape4"></div>
        <div class="shape5"></div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h2>Book an Appointment</h2>
            </div>
            <div class="card-body">
                <form id="booking-form">
                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>

                    <!-- Date Field -->
                    <div class="form-group">
                        <label for="date">Select Date</label>
                        <input type="text" class="form-control" id="date" name="date" placeholder="Pick a date" required>
                    </div>

                    <!-- Time Slot Field -->
                    <div class="form-group">
                        <label for="time_slot">Select Time Slot</label>
                        <select class="form-control" id="time_slot" name="time_slot" required>
                            <option value="">Select a time slot</option>
                            <!-- Options will be dynamically filled by AJAX -->
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block">Book Appointment</button>
                </form>

                <!-- Message Section -->
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // Initialize datepicker with disabled Sundays and previous dates
            $("#date").datepicker({
                dateFormat: 'yy-mm-dd',
                beforeShowDay: function(date) {
                    var day = date.getDay();
                    // Disable Sundays and highlight fully booked days
                    return [(day != 0), ''];
                },
                onSelect: function(dateText) {
                    // Fetch available time slots for the selected date
                    $.ajax({
                        url: 'fetch_slots.php',
                        type: 'POST',
                        data: {
                            date: dateText
                        },
                        success: function(data) {
                            $('#time_slot').html(data);
                        }
                    });
                }
            });

            // Form submission via AJAX
            $("#booking-form").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'book_appointment.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.status === 'success') {
                            $("#message").html('<div class="alert alert-success">' + res.message + '</div>');
                            $("#booking-form").hide();
                        } else {
                            $("#message").html('<div class="alert alert-danger">' + res.message + '</div>');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>