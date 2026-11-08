
<?php

// Electricity tariff rates
$rates = [0.218, 0.334, 0.516, 0.546, 0.571];

// Maximum usage for each block
$limits = [200, 100, 300, 300, null];

// Default values
$usage = ["", "", "", "", ""];
$errors = [];
$calculated = false;

// When Calculate button is clicked
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['calculate'])) {

    $numbers = [];

    // Get and validate electricity usage
    for ($i = 0; $i < 5; $i++) {

        $value = $_POST['usage'][$i] ?? "";
        $value = is_scalar($value) ? trim((string)$value) : "";
        $usage[$i] = $value;

        if (
            $value === "" ||
            !is_numeric($value) ||
            !is_finite((float)$value) ||
            (float)$value < 0
        ) {
            $errors[] = "Please enter a valid number for block " . ($i + 1) . ".";
            continue;
        }

        if ($limits[$i] !== null && (float)$value > $limits[$i]) {
            $errors[] = "Block " . ($i + 1) .
                        " cannot exceed " . $limits[$i] . " kWh.";
            continue;
        }

        $numbers[$i] = (float)$value;
    }

    // Check previous tariff blocks
    if (empty($errors)) {

        for ($i = 1; $i < 5; $i++) {

            if (
                $numbers[$i] > 0 &&
                $numbers[$i - 1] < $limits[$i - 1]
            ) {
                $errors[] = "Please complete the previous tariff block before entering usage in block " . ($i + 1) . ".";
                break;
            }
        }
    }

    // Calculate electricity bill
    if (empty($errors)) {

        $charge1 = $numbers[0] * 0.218;
        $charge2 = $numbers[1] * 0.334;
        $charge3 = $numbers[2] * 0.516;
        $charge4 = $numbers[3] * 0.546;
        $charge5 = $numbers[4] * 0.571;

        // Store individual charges
        $bills = [
            $charge1,
            $charge2,
            $charge3,
            $charge4,
            $charge5
        ];

        // Total electricity consumption
        $totalUsage = array_sum($numbers);

        // Total electricity charges
        $totalCharges = array_sum($bills);

        // Minimum monthly charge RM3.00
        $totalBill = max(3.00, $totalCharges);

        $calculated = true;
    }
}
?>
