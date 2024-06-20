<?php
// dashboard.php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch events from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, title, start_date FROM events WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
</head>
<body>
    <div id='calendar'></div>
    <!-- Bootstrap Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="form-group">
                            <label for="eventTitle">Event Title</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="form-group">
                            <label for="eventStart">Start Time</label>
                            <input type="time" class="form-control" id="eventStart" required>
                        </div>
                        <div class="form-group">
                            <label for="eventEnd">End Time</label>
                            <input type="time" class="form-control" id="eventEnd">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Save</button>
                    <button type="button" class="btn btn-success" id="updateEvent">Update</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var selectedEvent = null;

            $('#calendar').fullCalendar({
                events: 'fetch_events.php',  // Fetch events from the server
                dayClick: function(date, jsEvent, view) {
                    selectedEvent = null;
                    $('#eventForm')[0].reset();
                    $('#saveEvent').show();
                    $('#updateEvent').hide();
                    $('#deleteEvent').hide();
                    $('#eventModal').modal('show');
                    $('#saveEvent').off('click').on('click', function() {
                        var title = $('#eventTitle').val();
                        var start = date.format('YYYY-MM-DD') + 'T' + $('#eventStart').val();
                        var end = $('#eventEnd').val() ? date.format('YYYY-MM-DD') + 'T' + $('#eventEnd').val() : null;

                        $.ajax({
                            url: 'add_event.php',
                            method: 'POST',
                            data: { title: title, start_date: start, end_date: end },
                            success: function() {
                                $('#calendar').fullCalendar('refetchEvents');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error adding event:', status, error);
                            }
                        });

                        $('#eventModal').modal('hide');
                    });
                },
                eventClick: function(event, jsEvent, view) {
                    selectedEvent = event;
                    $('#eventTitle').val(event.title);
                    $('#eventStart').val(moment(event.start).format('HH:mm'));
                    $('#eventEnd').val(event.end ? moment(event.end).format('HH:mm') : '');
                    $('#saveEvent').hide();
                    $('#updateEvent').show();
                    $('#deleteEvent').show();
                    $('#eventModal').modal('show');

                    $('#updateEvent').off('click').on('click', function() {
                        var title = $('#eventTitle').val();
                        var start = moment(event.start).format('YYYY-MM-DD') + 'T' + $('#eventStart').val();
                        var end = $('#eventEnd').val() ? moment(event.start).format('YYYY-MM-DD') + 'T' + $('#eventEnd').val() : null;

                        $.ajax({
                            url: 'update_event.php',
                            method: 'POST',
                            data: { id: event.id, title: title, start: start, end: end },
                            success: function() {
                                $('#calendar').fullCalendar('refetchEvents');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating event:', status, error);
                            }
                        });

                        $('#eventModal').modal('hide');
                    });

                    $('#deleteEvent').off('click').on('click', function() {
                        $.ajax({
                            url: 'delete_event.php',
                            method: 'POST',
                            data: { id: event.id },
                            success: function() {
                                $('#calendar').fullCalendar('refetchEvents');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting event:', status, error);
                            }
                        });

                        $('#eventModal').modal('hide');
                    });
                }
            });

            // Fetch and display holidays //api
            $.ajax({
                url: 'https://api.api-ninjas.com/v1/holidays',
                method: 'GET',
                data: {
                    country: 'india',  // Replace 'india' with the desired country code//
                    year: 2024,        // Replace 2024 with the desired year
                    type: ''           // Replace '' with the desired holiday type if needed
                },
                headers: { 
                    'X-Api-Key': 'fpCxKcXVGGgE807rBdGo0g==f62D9cgDZErKRFnu'  // Replace with your actual API key
                },
                success: function(data) {
                    // Add holidays to the calendar
                    var holidays = data.map(function(holiday) {
                        return {
                            title: holiday.name,
                            start: holiday.date,
                            color: 'red'
                        };
                    });
                    $('#calendar').fullCalendar('addEventSource', holidays);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching holidays:', status, error);
                }
            });
        });
    </script>
</body>
</html>
