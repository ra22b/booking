-- Table for storing city information
CREATE TABLE cities (
    city_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each city
    city_name VARCHAR(100) NOT NULL               -- Name of the city
);

-- Table for storing specialization information
CREATE TABLE specializations (
    specialization_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each specialization
    specialization_name VARCHAR(100) NOT NULL,                        -- Name of the specialization
    description TEXT                                   -- Description of the specialization
);

-- Table for storing doctor information
CREATE TABLE doctors (
    doctor_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each doctor
    doctor_name VARCHAR(100) NOT NULL,                -- Name of the doctor
    specialization_id INT,                     -- Foreign key to the specializations table
    city_id INT,                               -- Foreign key to the cities table
    clinic_address VARCHAR(255),               -- Address of the clinic
    description_spe  VARCHAR(255),               -- Address of the clinic
    working_days VARCHAR(100),                 -- Working days of the doctor
    working_hours VARCHAR(100),                -- Working hours of the doctor
    rating FLOAT,                              -- Rating of the doctor
    profile_image VARCHAR(255),                -- URL or path to the doctor's profile image
    contact_number VARCHAR(15),                -- Contact number of the doctor
    email VARCHAR(100),                        -- Email address of the doctor
    gender ENUM('Male', 'Female', 'Other'),
    password VARCHAR(255) NOT NULL ,
    FOREIGN KEY (specialization_id) REFERENCES specializations(specialization_id),  -- Linking specialization_id
    FOREIGN KEY (city_id) REFERENCES cities(city_id)                                -- Linking city_id
);

-- Table for storing patient information
CREATE TABLE patients (
    patient_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each patient
    patient_name VARCHAR(100) NOT NULL,         -- Name of the patient
    gender ENUM('Male', 'Female', 'Other') NOT NULL,  -- Gender of the patient
    age INT NOT NULL,                           -- Age of the patient
    contact_number VARCHAR(15),                -- Contact number of the patient
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL             -- Password for the patient (hash it for security)
);
-- Table for storing appointment information
CREATE TABLE appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each appointment
    patient_id INT,                                 -- Foreign key to the patients table
    doctor_id INT,                                  -- Foreign key to the doctors table
    appointment_date DATE,                          -- Date of the appointment
    appointment_time TIME,                          -- Time of the appointment
    stat ENUM('Confirmed', 'Cancelled', 'Pending'),  -- Status of the appointment
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),  -- Linking patient_id
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)      -- Linking doctor_id
);

-- Table for storing reviews and ratings of doctors by patients
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each review
    patient_id INT,                            -- Foreign key to the patients table
    doctor_id INT,                             -- Foreign key to the doctors table
    rating INT CHECK (rating >= 1 AND rating <= 5),  -- Rating given by the patient (1 to 5)
    comment TEXT,                              -- Review comment provided by the patient
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),  -- Linking patient_id
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)      -- Linking doctor_id
);

-- Table for storing admin information
CREATE TABLE admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,  -- Unique ID for each admin
    name_admin VARCHAR(100) NOT NULL,               -- Name of the admin
    password VARCHAR(255) NOT NULL,           -- Password for login (should be stored securely)
    email VARCHAR(100),                       -- Email address of the admin
    contact_number VARCHAR(15)                -- Contact number of the admin
);
