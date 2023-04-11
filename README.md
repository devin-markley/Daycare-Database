# Western Slope Daycare Management
## Introduction

I create this website as part of an interview project and   during the experience I learned php, sql/psql, and had an introduction to database design. The web application is designed to help manage various aspects of a childcare program, including providers, children, meals, and attendance. The application uses a PostgreSQL database to store data. 

## Website Features

- Retrieve all providers from the database.
- Retrieve children associated with a given provider.
- Retrieve active children associated with a given provider.
- Add a child to the database.
- Add a meal and attendance information to the database.
- Update the active status of children in the database.
- Generate an individual attendance report for a specific child.
- Generate a monthly attendance report for a specific provider.

# Project Setup
## Dependency Installation

    1.Install Apache, PHP, and PostgreSQL on your system using your system's package manager.

    2.Download and install the TCPDF library:
        Download the latest version of TCPDF from the TCPDF website.
        Extract the TCPDF files to your web server's document root directory.

    3.Clone the project repository to your local machine using the following command:

    git clone https://github.com/your-username/your-project.git

## Database Setup

    1.Create a new folder in your projects root directory called config
    2.Create a new file called database.php containing the following code:

        <?php
            function setupdb() {
            $dsn = 'pgsql:host='yourhostname';dbname='yourdatabasename';
            $username = 'yourusername';
            $password = 'password';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            try {
                $pdo = new PDO($dsn, $username, $password, $options);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        ?>
    3. Login to your data using the following command: psql -h  -U pr8w3j -d pr8w3j

    4. Create the following tables

            -- Create provider table
            CREATE TABLE provider (
                provider_id SERIAL PRIMARY KEY,
                provider_name VARCHAR(255) NOT NULL
            );

            -- Create child table
            CREATE TABLE child (
                child_id SERIAL PRIMARY KEY,
                provider_id INTEGER NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                starting_date DATE NOT NULL,
                active_status BOOLEAN NOT NULL,
                FOREIGN KEY (provider_id) REFERENCES provider(provider_id)
            );

            -- Create meal_content table
            CREATE TABLE meal_content (
                meal_content_id SERIAL PRIMARY KEY,
                fruit VARCHAR(255) NOT NULL,
                vegetables VARCHAR(255) NOT NULL
            );

            -- Create meal table
            CREATE TABLE meal (
                meal_id SERIAL PRIMARY KEY,
                meal_content_id INTEGER NOT NULL,
                date_served DATE NOT NULL,
                FOREIGN KEY (meal_content_id) REFERENCES meal_content(meal_content_id)
            );

            -- Create attendance table
            CREATE TABLE attendance (
                attendance_id SERIAL PRIMARY KEY,
                child_id INTEGER NOT NULL,
                meal_id INTEGER NOT NULL,
                provider_id INTEGER NOT NULL,
                FOREIGN KEY (child_id) REFERENCES child(child_id),
                FOREIGN KEY (meal_id) REFERENCES meal(meal_id),
                FOREIGN KEY (provider_id) REFERENCES provider(provider_id)
            );

# Database Schema
![alt text](/img/DatabaseSchema.png)


# Support

if you encounter any issues or have questions about this project please contact me at <devin.markley@trojans.dsu.edu>

# License

This project is licensed under the MIT License
