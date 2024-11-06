<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .event-form {
            max-width: 800px;
            margin: 30px auto;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            color: #333;
            font-size: 24px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 15px;
        }

        .required {
            color: #ff0000;
            margin-left: 3px;
        }

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.3);
        }

        select {
            background-color: #fff;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 30px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input[type="file"] {
            padding: 10px 0;
            cursor: pointer;
        }

        .price-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .error-message {
            color: #ff0000;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .create-btn {
            background-color: #00b9f5;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .create-btn:hover {
            background-color: #ff006e;
            transform: translateY(-1px);
        }


    
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
            height: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="process_event.php" class="event-form" method="post" enctype="multipart/form-data" id="eventForm">
            <h2 class="form-title">Create New Event</h2>

            <div class="form-group">
                <label>Category <span class="required">*</span></label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <option value="1">Movie</option>
                    <option value="2">Comedy</option>
                    <option value="3">Sports</option>
                    <option value="4">Concerts</option>
                </select>
                <span class="error-message" id="categoryError"></span>
            </div>

            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" required>
                <span class="error-message" id="titleError"></span>
            </div>

            <div class="form-group">
                <label>Description <span class="required">*</span></label>
                <textarea name="description" required></textarea>
                <span class="error-message" id="descriptionError"></span>
            </div>

            <div class="form-group">
                <label>Event Image <span class="required">*</span></label>
                <input type="file" name="image_url" required accept="image/*">
                <span class="error-message" id="imageError"></span>
            </div>

            <div class="form-group">
                <label>Venue <span class="required">*</span></label>
                <input type="text" name="venue" required>
                <span class="error-message" id="venueError"></span>
            </div>

            <div class="form-group">
                <label>Event Date & Time <span class="required">*</span></label>
                <input type="datetime-local" name="event_datetime" required>
                <span class="error-message" id="dateTimeError"></span>
            </div>

            <div class="form-group price-group">
                <div>
                    <label>Platinum Price <span class="required">*</span></label>
                    <input type="number" name="price_platinum" required min="0">
                    <span class="error-message" id="platinumError"></span>
                </div>
                <div>
                    <label>Gold Price <span class="required">*</span></label>
                    <input type="number" name="price_gold" required min="0">
                    <span class="error-message" id="goldError"></span>
                </div>
                <div>
                    <label>Silver Price <span class="required">*</span></label>
                    <input type="number" name="price_silver" required min="0">
                    <span class="error-message" id="silverError"></span>
                </div>
            </div>

            <div class="form-group">
                <label>Total Seats <span class="required">*</span></label>
                <input type="number" name="total_seats" required min="1">
                <span class="error-message" id="seatsError"></span>
            </div>

            <div class="form-group">
                <label>Status <span class="required">*</span></label>
                <select name="status" required>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <span class="error-message" id="statusError"></span>
            </div>

            <button type="submit" class="create-btn">Create Event</button>
        </form>
    </div>
</body>

</html>