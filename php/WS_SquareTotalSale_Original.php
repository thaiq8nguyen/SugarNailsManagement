
<?php
    # Demonstrates generating a 2015 payments report with the Square Connect API.
    # Replace the value of the `$accessToken` variable below before running this script.
    #
    # This sample assumes all monetary amounts are in US dollars. You can alter the
    # formatMoney function to display amounts in other currency formats.
    #
    # This sample requires the Unirest PHP library. Download it here:
    # http://unirest.io/php.html
    #
    # Results are rendered in a simple HTML pre block.
    # Replace this value with the path to the Unirest PHP library
    require_once '../phplib/UnirestPHP/Unirest.php';

    #if(isset($_GET['saleDate'])){
        # Replace this value with your application's personal access token,
        # available from your application dashboard (https://connect.squareup.com/apps)
        $accessToken = 'fJFktYv8MuIXzCYXPv-s2w';
        # The base URL for every Connect API request
        $connectHost = 'https://connect.squareup.com';
        # Standard HTTP headers for every Connect API request
        $requestHeaders = array (
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );
        $beginDate = "2016-08-01"; //$_GET['saleDate'];
        $date = new DateTime($beginDate);
        $date->modify('+1 day');
        $endDate = $date->format('Y-m-d');

        $payments = getTodayPayment(getLocationIds(),$beginDate,$endDate);
        print_r($payments);
        //echo json_encode(getTodaySale($payments));


        # Helper function to convert cent-based money amounts to dollars and cents
        function formatMoney($money) {
            return money_format('%+.2n', $money / 100);
        }
        # Obtains all of the business's location IDs. Each location has its own collection of payments.
        function getLocationIds() {
            //$locationID = "";
            global $accessToken, $connectHost, $requestHeaders;
            $requestPath = $connectHost . '/v1/me/locations';
            $response = Unirest\Request::get($requestPath, $requestHeaders);
            $locations = $response->body;
            /*
            foreach ($locations as $location) {
                $locationID = $location->id;
            }
            */
            return $locations[0]->id;

        }

        function getTodayPayment($locationID,$beginDate,$endDate){

            global $accessToken, $connectHost, $requestHeaders;

            $parameters = http_build_query(
                array(
                    'begin_time' => $beginDate . 'T00:00:00-08:00',
                    'end_time'   => $endDate .'T00:00:00-08:00'
                )
            );
            $payments = array();


            $requestPath = $connectHost . '/v1/'.$locationID.'/payments?' . $parameters;
            $moreResults = true;

            while ($moreResults) {
                # Send a GET request to the List Payments endpoint
                $response = Unirest\Request::get($requestPath, $requestHeaders);
                # Read the converted JSON body into the cumulative array of results
                $payments = array_merge($payments, $response->body);
                # Check whether pagination information is included in a response header, indicating more results
                if (array_key_exists('Link', $response->headers)) {
                    $paginationHeader = $response->headers['Link'];
                    if (strpos($paginationHeader, "rel='next'") !== false) {
                        # Extract the next batch URL from the header.
                        #
                        # Pagination headers have the following format:
                        # <https://connect.squareup.com/v1/MERCHANT_ID/payments?batch_token=BATCH_TOKEN>;rel='next'
                        # This line extracts the URL from the angle brackets surrounding it.
                        $requestPath = explode('>', explode('<', $paginationHeader)[1])[0];
                    }
                    else {
                        $moreResults = false;
                    }
                }
                else {
                    $moreResults = false;
                }
            }


            # Remove potential duplicate values from the list of payments
            $seenPaymentIds = array();
            $uniquePayments = array();
            foreach ($payments as $payment) {
                if (array_key_exists($payment->id, $seenPaymentIds)) {
                    continue;
                }
                $seenPaymentIds[$payment->id] = true;
                array_push($uniquePayments, $payment);
            }

            return $payments;
        }

        function getTodaySale($payments){

            $totalCollected = 0;
            $totalTipOnCard = 0;


            foreach($payments as $payment){

                $totalCollected = $totalCollected + $payment->total_collected_money->amount;
                $totalTipOnCard =  $totalTipOnCard + $payment->tip_money->amount;

            }
            $grossSales = $totalCollected - $totalTipOnCard;

            return array("grossSale" => formatMoney($grossSales), "grossTip" => formatMoney($totalTipOnCard));
        }

    #}

