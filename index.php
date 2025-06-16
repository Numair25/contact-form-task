<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        /* Your existing styles */
    </style>
</head>

<body>
    <div id="popup" style="display:none;">Thanks for submitting the form!</div>
    <main>
        <div id="contact">
            <form id="contactForm" action="contact.php" method="post">
                <h1>Contact Us</h1>
                <!-- Your existing form fields -->
                <div id="submitbtn">
                    <span id="ssubmit">
                        <label for="submit" style="width: 100%;">
                            <input type="submit" value="Submit">
                        </label>
                    </span>
                </div>
            </form>
        </div>
    </main>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost"; // Your server name
        $username = "your_db_username"; // Your database username
        $password = "your_db_password"; // Your database password
        $dbname = "contact_form"; // Your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get form data
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $queryType = $_POST['queryType'];
        $message = $_POST['message'];
        $consent = isset($_POST['consent']) ? 1 : 0;

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO submissions (firstname, lastname, email, query_type, message, consent) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $firstname, $lastname, $email, $queryType, $message, $consent);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>document.getElementById('popup').style.display = 'block';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close connections
        $stmt->close();
        $conn->close();
    }
    ?>

    <script>
            document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('contactForm');
            const fnameInput = document.getElementById('firstname');
            const lnameInput = document.getElementById('lastname');
            const emailInput = document.getElementById('email');
            const queryInputs = document.getElementsByName('queryType');
            const messageInput = document.getElementById('message');

            const fnameError = document.getElementById('fnameError');
            const lnameError = document.getElementById('lnameError');
            const emailError = document.getElementById('emailError');
            const queryError = document.getElementById('queryError');
            const messageError = document.getElementById('messageError');



            const validateName = (input, error) => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    error.style.display = 'block';
                }
                else {
                    input.classList.remove('error');
                    error.style.display = 'none';
                }
            };
            const hideError = (input, error) => {
                input.addEventListener('focus', () => {
                    input.classList.remove('error');
                    error.style.display = 'none';
                });
            };
            hideError(fnameInput, fnameError);
            hideError(lnameInput, lnameError);
            hideError(emailInput, emailError);
            hideError(messageInput, messageError);

            const validateEmail = () => {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailInput.value.trim())) {
                    emailInput.classList.add('error');
                    emailError.style.display = 'block';
                } else {
                    emailInput.classList.remove('error');
                    emailError.style.display = 'none';
                }
            };

            const validateQuery = () => {
                let isChecked = false;
                for (let i = 0; i < queryInputs.length; i++) {
                    if (queryInputs[i].checked) {
                        isChecked = true;
                        break;
                    }
                }
                if (!isChecked) {
                    queryError.style.display = 'block';
                } else {
                    queryError.style.display = 'none';
                }
            };

            const validateMessage = () => {
                if (!messageInput.value.trim()) {
                    messageInput.classList.add('error');
                    messageError.style.display = 'block';
                } else {
                    messageInput.classList.remove('error');
                    messageError.style.display = 'none';
                }
            };

            fnameInput.addEventListener('blur', () => validateName(fnameInput, fnameError));
            lnameInput.addEventListener('blur', () => validateName(lnameInput, lnameError));
            emailInput.addEventListener('blur', validateEmail);
            messageInput.addEventListener('blur', validateMessage);

            form.addEventListener('submit', (event) => {
                validateName(fnameInput, fnameError);
                validateName(lnameInput, lnameError);
                validateEmail();
                validateQuery();
                validateMessage();

                if (form.querySelector('.error') || queryError.style.display === 'block') {
                    event.preventDefault();
                } else {
                    const popup = document.getElementById('popup');
                    popup.style.display = 'block';
                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 3000);
                }
            });
        });
    </script>
</body>

</html>
