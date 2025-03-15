# NCSAT - Northeastern College School Ability Test

## Overview
The **School Ability Test (NCSAT)** is a standardized assessment used at **Northeastern College** to evaluate incoming freshmen students’ academic abilities. This project aims to enhance the current NCSAT system by upgrading it into a **secure, efficient, and user-friendly web-based application**. The system addresses key challenges such as:

- Compatibility issues with modern technology
- Slow data retrieval
- Lack of performance analysis tools
- Security risks during exams

## Features
- **Graphical Performance Analysis**: Uses **Item Response Theory (IRT)** to generate assessment graphs.
- **Detailed Reports**: Provides printout reports based on student performance.
- **Instructor Portal**: Enables instructors to analyze results and adjust teaching strategies accordingly.
- **One-Time Password (OTP) Security**: Ensures only the intended student can log in for each exam session.
- **Optimized Database**: Improves data retrieval speed and performance.

## Technologies Used
- **Laravel 11** (Backend Framework)
- **Vue.js 3** (Frontend Framework)
- **PHP 8.02** (via XAMPP)
- **MySQL** (Database)


## System Architecture
The development process follows the **waterfall model**, ensuring a structured and sequential approach to achieving project objectives.

### Backend
The backend is built with **Laravel 10**, providing a robust and scalable architecture to handle exam-related functionalities securely. It includes:
- Authentication and authorization mechanisms
- Secure data handling and retrieval
- Performance analytics processing

### Frontend
The frontend is powered by **Vue.js**, ensuring a modern and interactive user interface. It includes:
- Real-time exam monitoring
- Interactive performance visualization
- Responsive design for accessibility across devices

### Security Measures
- **OTP-based authentication** for exam security
- **Data encryption** to protect student information
- **Role-based access control (RBAC)** for secure administration

## Installation Guide
### Prerequisites
- PHP 8.02
- Composer
- Node.js & NPM
- MySQL Database
- XAMPP (optional for local development)

### Steps
1. **Clone the Repository:**
   ```sh
   git clone https://github.com/your-repo/ncsat.git
   cd ncsat
   ```
2. **Install Backend Dependencies:**
   ```sh
   composer install
   ```
3. **Install Frontend Dependencies:**
   ```sh
   npm install
   ```
4. **Configure Environment:**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
5. **Run Database Migrations:**
   ```sh
   php artisan migrate --seed
   ```
6. **Start Development Server:**
   ```sh
   php artisan serve
   ```
7. **Run Vue Development Server:**
   ```sh
   npm run dev
   ```

## Contributing
Contributions are welcome! To contribute:
1. Fork the repository
2. Create a feature branch (`git checkout -b feature-name`)
3. Commit changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature-name`)
5. Create a pull request

## License
This project is licensed under the MIT License.

## Contact
For inquiries or support, please reach out to:
- **Email**: support@northeastern.edu
- **GitHub Issues**: Open an issue in the repository

