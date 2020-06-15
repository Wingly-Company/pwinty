# Contributing

Contributions are welcome and will be fully credited.

Contributions are accepted via Pull Requests on [Github](https://github.com/Wingly-Company/pwinty).

# Testing
You will need to set the Pwinty enviroment variables in a custom `phpunit.xml` file in order to run the tests.

Copy the default file using `cp phpunit.xml.dist phpunit.xml` and add the following lines below the `DB_CONNECTION` environment variable in your new `phpunit.xml` file:

        <env name="PWINTY_API_KEY" value="Your Pwinty Api Key"/>
        <env name="PWINTY_MERCHANT_ID" value="Your Pwinty Merchant ID"/>
        <env name="PWINTY_SKU" value="A Pwinty Valid Testing SKU"/>



