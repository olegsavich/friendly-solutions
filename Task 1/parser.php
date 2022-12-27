<?php
    error_reporting(E_ERROR | E_PARSE); // added to remove warning for dublication of location_address in wo_for_parse.html

    $doc = new DOMDocument();
    $doc->loadHTMLFile("wo_for_parse.html");
    $trackingNumber = $doc->getElementById("wo_number")->textContent;
    $poNumber = $doc->getElementById("po_number")->textContent;
    $date = $doc->getElementById("scheduled_date")->textContent;
    $formatedDate = date("Y-m-d H:i", strtotime(str_replace(["\n", "\r"], '', $date)));
    $customer = $doc->getElementById("location_customer")->textContent;
    $trade = $doc->getElementById("trade")->textContent;
    $nte = floatval(preg_replace('/[^.0-9\-]/', '', $doc->getElementById("nte")->textContent));
    $storeId = $doc->getElementById("location_name")->textContent;
    $location = explode("\n", trim($doc->getElementById("location_address")->textContent));
    $street = $location[0];
    $city = $doc->getElementById("store_id")->textContent;
    $state = preg_replace("/[^A-z\-]/", "", str_replace($city, "", $location[1]));
    $zip = str_replace(array($city, $state, " "), "", $location[1]);
    $phone = floatval(str_replace("-", "", $doc->getElementById("location_phone")->textContent));

    $list = [
        [
            "Tracking Number",
            "PO Number",
            "Scheduled Date",
            "Customer",
            "Trade",
            "NTE",
            "Store ID",
            "Street",
            "City",
            "State",
            "Zip",
            "Phone"
        ],
        [
            $trackingNumber,
            $poNumber,
            $formatedDate,
            $customer,
            $trade,
            $nte,
            $storeId,
            $street,
            $city,
            $state,
            $zip,
            $phone
        ]
    ];

    if (!file_exists('data.csv')) {
        touch('data.csv');
    }

    $fp = fopen('data.csv', 'r+');

    foreach ($list as $fields) {
        fwrite($fp, implode(',', $fields) . PHP_EOL);
    }

    fclose($fp);
?>