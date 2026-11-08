
<?php
// Include calculation process
require_once 'process.php';

// Default values if not already set by process.php
$usage = $usage ?? ["", "", "", "", ""];
$errors = $errors ?? [];
$calculated = $calculated ?? false;

$resultLabels = [
    "The charge for the first 200 kWh",
    "The charge for the next 100 kWh",
    "The charge for the next 300 kWh",
    "The charge for the next 300 kWh",
    "The charge for usage above 900 kWh"
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Calculate House Electricity Bill</title>

    <style>

        /* GENERAL PAGE */

        body {
            font-family: "Times New Roman", serif;
            font-size: 17px;
            margin: 20px;
            background-color: white;
            color: black;
        }

        h1 {
            font-size: 36px;
            margin-top: 15px;
            margin-bottom: 35px;
        }

        /* TARIFF TABLE */

        table {
            border: 1px solid gray;
            border-spacing: 3px;
            margin-bottom: 40px;
        }

        th, td {
            border: 1px solid gray;
            padding: 7px 10px;
            font-size: 17px;
        }

        th {
            font-size: 19px;
            font-weight: bold;
            text-align: center;
        }

        /* FORM */

        .form-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 17px;
            flex-wrap: wrap;
        }

        .form-row label {
            font-size: 17px;
        }

        .form-row input {
            width: 220px;
            height: 35px;
            padding: 5px 10px;
            font-size: 16px;
            border: 1px solid gray;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* BUTTONS */

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            margin-bottom: 30px;
        }

        button {
            font-size: 16px;
            padding: 8px 20px;
            border: 1px solid gray;
            border-radius: 4px;
            background-color: #f0f0f0;
            cursor: pointer;
        }

        button:hover {
            background-color: #dddddd;
        }

        /* RESULTS */

        .results {
            margin-top: 20px;
            margin-bottom: 40px;
        }

        .results h2 {
            font-size: 24px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .result-row {
            margin-bottom: 8px;
            font-size: 17px;
        }

        .total {
            font-size: 21px;
            font-weight: bold;
            margin-top: 22px;
        }

        /* ERROR MESSAGES */

        
        .error {
            color: red;
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        /* MOBILE DISPLAY */

        @media (max-width: 768px) {

            body {
                margin: 15px;
            }

            h1 {
                font-size: 29px;
            }

            .table-container {
                overflow-x: auto;
            }

            th, td {
                font-size: 15px;
                padding: 7px;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .form-row label {
                font-size: 16px;
            }

            .form-row input {
                width: 100%;
                max-width: 320px;
            }

        }

    </style>

</head>

<body>

    <h1>Calculate House Electricity Bill</h1>

    <!-- ELECTRICITY TARIFF TABLE -->

    <div class="table-container">

        <table>

            <tr>
                <th>Block Tariff (per month)</th>
                <th>Unit</th>
                <th>Rate</th>
            </tr>

            <tr>
                <td>For the first 200 kWh (1-200 kWh) per month</td>
                <td>sen/kWh</td>
                <td>0.218</td>
            </tr>

            <tr>
                <td>For the next 100 kWh (201-300 kWh) per month</td>
                <td>sen/kWh</td>
                <td>0.334</td>
            </tr>

            <tr>
                <td>For the next 300 kWh (301-600 kWh) per month</td>
                <td>sen/kWh</td>
                <td>0.516</td>
            </tr>

            <tr>
                <td>For the next 300 kWh (601-900 kWh) per month</td>
                <td>sen/kWh</td>
                <td>0.546</td>
            </tr>

            <tr>
                <td>For the next kWh (901 kWh onwards) per month</td>
                <td>sen/kWh</td>
                <td>0.571</td>
            </tr>

            <tr>
                <td colspan="3">
                    The minimum monthly charge is RM3.00
                </td>
            </tr>

        </table>

    </div>


    <!-- ERROR MESSAGES ABOVE FORM -->

    <?php if (!empty($errors)): ?>

    <div class="error">

        <?php foreach ($errors as $error): ?>

            <p>
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </p>

        <?php endforeach; ?>

    </div>

<?php endif; ?>


    <!-- CALCULATION RESULTS ABOVE FORM -->

    <?php if ($calculated): ?>

        <div class="results">

            <h2>Electricity Usage Entered:</h2>

            <?php for ($i = 0; $i < 5; $i++): ?>

                <div class="result-row">

                    <?php echo $resultLabels[$i]; ?>:

                    <?php echo number_format($numbers[$i], 2); ?>
                    kWh

                </div>

            <?php endfor; ?>


            <h2>Electricity Bill Calculation:</h2>

            <?php for ($i = 0; $i < 5; $i++): ?>

                <div class="result-row">

                    <?php echo $resultLabels[$i]; ?>:

                    RM <?php echo number_format($bills[$i], 2); ?>

                </div>

            <?php endfor; ?>


            <div class="total">

                Total Electricity Consumption:
                <?php echo number_format($totalUsage, 2); ?> kWh

            </div>


            <div class="total">

                Total Electricity Bill:
                RM <?php echo number_format($totalBill, 2); ?>

            </div>

        </div>

    <?php endif; ?>


    <!-- ELECTRICITY CALCULATOR FORM -->

    <form method="POST" action="index.php">

        <div class="form-row">

            <label for="usage0">
                Enter your first 200 kWh (1 - 200 kWh) per month
            </label>

            <input
                type="number"
                id="usage0"
                name="usage[0]"
                min="0"
                max="200"
                step="any"
                value="<?php echo htmlspecialchars((string)$usage[0], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

        </div>


        <div class="form-row">

            <label for="usage1">
                Enter your next 100 kWh (201 - 300 kWh) per month
            </label>

            <input
                type="number"
                id="usage1"
                name="usage[1]"
                min="0"
                max="100"
                step="any"
                value="<?php echo htmlspecialchars((string)$usage[1], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

        </div>


        <div class="form-row">

            <label for="usage2">
                Enter your next 300 kWh (301 - 600 kWh) per month
            </label>

            <input
                type="number"
                id="usage2"
                name="usage[2]"
                min="0"
                max="300"
                step="any"
                value="<?php echo htmlspecialchars((string)$usage[2], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

        </div>


        <div class="form-row">

            <label for="usage3">
                Enter your next 300 kWh (601 - 900 kWh) per month
            </label>

            <input
                type="number"
                id="usage3"
                name="usage[3]"
                min="0"
                max="300"
                step="any"
                value="<?php echo htmlspecialchars((string)$usage[3], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

        </div>


        <div class="form-row">

            <label for="usage4">
                Enter your remaining kWh (901 kWh onwards) per month
            </label>

            <input
                type="number"
                id="usage4"
                name="usage[4]"
                min="0"
                step="any"
                value="<?php echo htmlspecialchars((string)$usage[4], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

        </div>


        <!-- BUTTONS -->

        <div class="buttons">

            <button type="submit" name="calculate" value="1">
                Calculate
            </button>

            <button
                type="button"
                onclick="window.location.href='index.php'"
            >
                Reset
            </button>

        </div>

    </form>


<!-- ELECTRICITY BILL RESULTS -->

<?php if ($calculated): ?>

    <div class="results">

        <h2>Electricity Bill</h2>

        <p>The charge for the first kWh is
            <?php echo round($bills[0], 2); ?>
        </p>

        <p>The charge for the second kWh is
            <?php echo round($bills[1], 2); ?>
        </p>

        <p>The charge for the third kWh is
            <?php echo round($bills[2], 2); ?>
        </p>

        <p>The charge for the fourth kWh is
            <?php echo round($bills[3], 2); ?>
        </p>

        <p>The charge for the fifth kWh is
            <?php echo round($bills[4], 2); ?>
        </p>

        <p>
            <strong>Total kWh used:</strong>
            <?php echo round($totalUsage, 2); ?>
        </p>

        <p>
            <strong>Total charge:</strong>
            RM<?php echo number_format($totalBill, 2); ?>
        </p>

    </div>

<?php endif; ?>



</body>
</html>
