# WordPress Post Scheduler

A simple WordPress tool that allows you to reschedule all future posts to publish at a regular interval (two posts per hour), making content scheduling more consistent and manageable.

## Features

- Reschedules all future posts to publish at regular intervals (two per hour)
- Uses London timezone (GMT) for consistent timing
- Simple one-click implementation from WordPress admin panel
- No configuration needed - just install and use
- Perfect for managing large batches of scheduled content

## Installation

### Method 1: As Plugin
1. Create a new folder called `wordpress-post-scheduler` in your `/wp-content/plugins/` directory
2. Add the `post-scheduler.php` file to this folder
3. Activate the plugin from your WordPress admin panel

### Method 2: In functions.php
1. Add the code to your theme's `functions.php` file
2. Save the file and upload it to your theme directory

## Usage

1. After installation, go to "Tools" > "Reschedule Posts" in your WordPress admin panel
2. Click the "Reschedule Posts" button
3. All your future (scheduled) posts will be rescheduled to publish at a rate of two posts per hour

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Access to WordPress admin panel with administrator privileges
- Scheduled (future) posts to reschedule

## Customization

You can modify the scheduling parameters in the code:

- To change the number of posts per hour, modify the `$minutes = ($index % 2) * 30;` line
- To change the timezone, modify the `$timezone = new DateTimeZone('Europe/London');` line
- To change the starting time, modify the `$start_time->modify('+1 hour');` line

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author

Hostragons Global Limited - https://www.hostragons.com

## Contribution

Contributions, issues, and feature requests are welcome!
