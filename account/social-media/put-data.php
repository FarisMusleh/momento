<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento - information form </title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            color: #000000;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            box-sizing: border-box;
        }
        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
        }
        input, select {
            width: 100%;
            padding: 14px;
            border: 1px solid #000000;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #333333;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Momento</div>
       
        <form id="registrationForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" pattern="\+?[0-9]{10,15}" placeholder="1234567890" required>
                
            </div>
            <div class="form-group">
                <label for="accountType">Account Type</label>
                <select id="accountType" name="accountType" required>
                    <option value="">Select Account Type</option>
                    <option value="personal">Personal</option>
                    <option value="business">Business</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <select id="location" name="location" required>
                    <option value="">Select Your Country</option>
                    <option value="United States">United States</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="Germany">Germany</option>
                    <option value="France">France</option>
                    <option value="Italy">Italy</option>
                    <option value="Spain">Spain</option>
                    <option value="Japan">Japan</option>
                    <option value="China">China</option>
                    <option value="India">India</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Mexico">Mexico</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Russia">Russia</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="Turkey">Turkey</option>
                    <option value="South Korea">South Korea</option>
                    <option value="Egypt">Egypt</option>
                </select>
            </div>
            <button type="submit">Confirm</button>
        </form>
    </div>
</body>
</html>