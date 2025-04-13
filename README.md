# User-lead-management


## Project Setup

1. Install XAMPP/WAMP (for local development) if not already installed.
2. Download or clone the repository into your `htdocs` folder (e.g., `C:\xampp\htdocs\ci3-user-management`).
3. Create a MySQL database `ci3_user_management` in phpMyAdmin.
4. Import the provided `database.sql` file to create necessary tables and sample data.
5. Configure the database connection:
   - Go to `application/config/database.php` and set your database credentials.
6. Configure the base URL:
   - Go to `application/config/config.php` and set `$config['base_url']` to your local project URL (`http://localhost/ci3-user-management`).

## Testing the Application

- **Lead CRUD Operations**:
  - Navigate to `http://localhost/ci3-user-management/leads` to view and manage leads.
  - Use the "Add Lead" button to create new leads.
  - Use the "Edit" and "Delete" options to update and remove leads.

- **Round Robin Lead Assignment**:
  - Leads will be assigned to users based on the round-robin logic.
  - Ensure that users are available in the `round_robin_users` table for the lead assignment.
