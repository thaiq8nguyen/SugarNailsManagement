<?php

    $client = new SoapClient("http://www.restfulwebservices.net/wcf/StockQuoteService.svc?wsdl");
    $parameter = array('request'=>'AAPL');
    $response = $client->GetStockQuote($parameter);
    $result = $response->GetStockQuoteResult;
    $currentPrice = $result->Last;
    $company = $result->Name;
    print_r("Comapny: ".$company."<br>Price: $".$currentPrice." per share");
