# Server Resource Monitoring System

This is an open-source server resource monitoring system designed to collect and visualize server resource metrics. Currently, it supports Linux servers only. The software is built using Laravel 12, PHP 8.2, MySQL database, and Filament 3.2 for the admin interface.

## Features

-   Collects metrics from server resources:

    -   **CPU Load** (%)
    -   **Memory Load** (%)
    -   **Swap Load** (%)
    -   **Network Traffic** (KB/s)
    -   **Disk Load (IOPS)**
    -   **Disk R/W Operations** (KB/s)

-   Monitor multiple servers at once.
-   Data is sent from a separate agent software, which is provided alongside the main system.
-   Simple, intuitive web interface for managing and visualizing metrics.
-   Built with modern PHP and Laravel technologies to ensure scalability and performance.
-   **Data Retention**: The software supports data retention for metric records, configurable per server being monitored. Metrics are stored for a specific retention period and are automatically deleted once they exceed the configured retention time.

## Prerequisites

Before you begin, ensure you have met the following requirements:

-   **Linux-based server** for server being monitored.
-   PHP 8.2 or higher
-   MySQL database

## Installation

1. Clone the repository to your local machine or server:

    ```bash
    git https://github.com/azrulhaifan/tiny-sysmon-server
    ```

2. Navigate into the project directory:

    ```bash
    cd tiny-sysmon-server
    ```

3. Install the required PHP dependencies:

    ```bash
    composer install
    ```

4. Set up your `.env` file for configuration:

    ```bash
    cp .env.example .env
    ```

5. Configure the database settings in `.env`:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

6. Run the database migrations:

    ```bash
    php artisan migrate
    ```

7. Serve the application locally:

    ```bash
    php artisan serve
    ```

8. Access the application via your browser at `http://localhost:8000/apps`.

9. You may add filament user with this command :

    ```bash
    php artisan make:filament-user
    ```

## Agent Installation

The monitoring system requires an agent installed on each server you want to monitor. The agent collects and sends metrics to the main server.

1. Configure the agent software from this [repo](https://github.com/azrulhaifan/tiny-sysmon-agent).

## Usage

Once the application is running, you can access the following features:

-   **Dashboard**: View a simple stats overview of total server and total metric record.
-   **Server Management**: Add and configure multiple servers to monitor.
-   **Metric Visualization**: Analyze and track resource usage over time.

## Planning

The following features are planned for future releases:

1. **Alert System**

    - Provide notifications via Telegram or email when a server resource exceeds predefined thresholds (e.g., high CPU load over a certain period of time, etc.).

2. **Notification Settings**

    - Allow users to configure notification media, including setting up Telegram and email server configurations.

3. **Launch SAAS Software**
    - In the future, this software will serve as the foundation for a new system supporting Software as a Service (SAAS) to allow broader deployment and scalability.

## Contribution

If you would like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -am 'Add feature'`).
4. Push to the branch (`git push origin feature-name`).
5. Create a new pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](https://github.com/azrulhaifan/tiny-sysmon-server/blob/main/LICENSE) file for details.

## Acknowledgments

-   Laravel 12 for the powerful backend framework.
-   Filament 3.2 for the beautiful admin panel.
-   The open-source community for continuous support and contributions.
