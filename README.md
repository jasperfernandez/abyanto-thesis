# Licensure Predictor

Plain PHP, MySQL, Tailwind CDN, CSS, and JavaScript app for editing Mechanical Engineering student grades and predicting licensure result.

## Setup

1. Create and seed the MySQL database:

```bash
mysql -u root -p < sql/database.sql
```

2. Configure database credentials if your local MySQL user is not `root` with no password:

```bash
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_DATABASE=licensure_predictor
export DB_USERNAME=root
export DB_PASSWORD=your_password
```

3. Run the PHP app:

```bash
php -S 127.0.0.1:8000
```

4. Open `http://127.0.0.1:8000/index.php`.

## Prediction Rule

The prediction uses only major course grades. In this build, major courses are course codes beginning with `ME ` or `AME `. If the major-course average is greater than or equal to `2.49`, the prediction is `FAIL`; otherwise it is `PASS`.
