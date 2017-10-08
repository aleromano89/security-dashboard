# security-dashboard
A dashboard that shows the status of security features on .gov.it websites


## Installation and usage Domain Scan Tool 

1. Install [domain-scan](https://github.com/18F/domain-scan/ "domain-scan")
2. Clone the project into web server root ex. /home/public_html
    ```bash
    cd /home/public_html
    git clone https://github.com/aleromano89/security-dashboard
    cd security-dashboard
    ```
3. Manually scan the csv file containing domains list to create the result data (may take some minutes)
    ```bash
    ./scan domains.csv --scan=pshtt
    ```
4. Copy your result file into web server root. The output csv should have the following columns in order to be supported: Domain,Base Domain,Live,Redirect,Valid HTTPS,Domain Enforces HTTPS
5. Open the dashboard in your web browser Ex. http://localhost/security-dashboard/

# Credits

Powered by Alessandro Romano