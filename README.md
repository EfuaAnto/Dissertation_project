# Dissertation_project
# alerting functions Setup Guide

## Prerequisites

- PHP installed on your system
- Web server (e.g., WAMP, XAMPP) with the project directory set up

## Running the Script Manually

1. Open a terminal or command prompt.
2. Navigate to the project directory:

    ```sh
    cd /c:/wamp64/www/Dissertation_project/
    ```

3. Run the script:

    ```sh
    /usr/bin/php php/FetchDataForAlerts.php
    ```

## Setting Up a Scheduled Task (Windows)

1. Open Task Scheduler (Press `Win + R`, type `taskschd.msc`, and press Enter).
2. Click on **Create Basic Task**.
3. Follow the prompts to set the task to run daily at midnight, and select the `run_fetch_data_for_alerts.sh` script.

## Setting Up a Cron Job (Linux/macOS)

1. Open the terminal.
2. Edit the crontab file:

    ```sh
    crontab -e
    ```

3. Add the following line to run the script at midnight every day:

    ```sh
    0 0 * * * /c:/wamp64/www/Dissertation_project/run_fetch_data_for_alerts.sh
    ```

This will ensure that the [FetchDataForAlerts.php](http://_vscodecontentref_/2) script runs every midnight.