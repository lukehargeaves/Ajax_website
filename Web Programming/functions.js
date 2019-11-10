/*This is the javascript function fil;e for the orderRecordsForm.php*/
/*Adds the eventListner method.*/
window.addEventListener('load', function () {
    'use strict';
    /*Methods for the events. This is where all of the functions are called based on events or set intervals.*/
    const l_form = document.getElementById('orderForm');
    l_form.submit.onclick = checkForm;
    l_form.termsChkbx.onclick = checkBox;
    window.setInterval(custType, 2000);
    window.setInterval(checkChecker, 2000);
    window.setInterval(delivery, 2000);

    /*Fills the variables with the infromation needed from the orderRecordsForm.php to act on.*/
    var check = document.getElementById('termsText');
    var button = document.querySelector("input[name=submit]");
    var checking = false;
    var checked = false;

    /*This function checks to make sure the checkbox has been checked. Depending on the result the conditional loop
    * occurs. This function changes the text and colour and also disables and enables the submit button at the
    * bottom of the page.*/
    function checkBox() {
        'use strict';
        checking = !checking;
        if (checking) {
            check.style.color = "black";
            check.style.fontWeight = "normal";
            button.value = "Order now!"
            button.disabled = false;
        }

        else {
            check.style.color = "red";
            check.style.fontWeight = "bold";
            button.value = "Button Disabled";
            button.disabled = true;
        }
    }

    var l_total = 0;

    /*This function checks to make sure that the records have been selected for the validation function later on. This
    * function also calculates a running total of the cost of the records being selected in the form and changes the
    * value in the value box further down on the orderRecords.php page.*/
    function checkChecker() {
        'use strict';
        const checkboxForm = document.querySelectorAll('input[data-price][type=checkbox]');
        const l_checkboxLength = checkboxForm.length;
        var n = 0;
        for (n = 0; n < l_checkboxLength; n += 1) {
            const t_box = checkboxForm[n];
            const t_checked = t_box.checked;
            if (t_checked == !null) {
                checked = true;
                var price = t_box.dataset.price;
                l_total = parseFloat(parseFloat(price) + parseFloat(l_total));
            }
        }
        l_total = parseFloat(l_total) + parseFloat(l_delivery);
        l_form.total.value = l_total;
        l_total = 0;

    }

    var l_delivery = 0;

    /*This function calculates the running cost of the selected delivery option. It then returns the information
    * to the previous function for the total running costs of the records selected and the delivery.*/
    function delivery() {
        'use strict';
        const delcheckform = document.querySelectorAll('input[name = deliveryType][type=radio]');
        const l_delcheckform = delcheckform.length;
        var n = 0;
        for (n = 0; n < l_delcheckform; n += 1) {
            const t_del = delcheckform[n];
            const t_checked = t_del.checked;
            if (t_checked == !null) {
                if (t_del.dataset.price == "0") {
                    l_delivery = 0;
                }
                else if (t_del.dataset.price == "5.99") {
                    l_delivery = 5.99;
                }
            }
        }
    }

    const showTrade = document.getElementById('tradeCustDetails');
    const showCust = document.getElementById('retCustDetails');

    var cust = true;
    var trade = false;

    /*This function runs to check whether it is a trade customer or just a customer. Depending on the condition
    * the style of the relevant form is changed showing visible of hidden.*/
    function custType() {
        'use strict';
        if (l_form.customerType.value == "ret") {
            showTrade.style.visibility = "hidden";
            showCust.style.visibility = "visible";
            cust = true;
            trade = false;
        }
        else if (l_form.customerType.value == "trd") {
            showTrade.style.visibility = "visible";
            showCust.style.visibility = "hidden";
            cust = false;
            trade = true;
        }
    }

    /*This is the validation function. It is there to make sure all of the relevant boxes of the form have been
    * fulfilled. If not then the _evt.preventDefault will prevent the data from being submitted and will return
    * the relevant alert to the user about the failed field.*/
    function checkForm(_evt) {
        'use strict';
        alert("Validating form input");
        var l_failed = false;
        if (cust == true) {
            if (l_form.forename.value == "") {
                alert("Forename not entered");
                l_failed = true;
            }

            if (l_form.surname.value == "") {
                alert("Surname not entered");
                l_failed = true;
            }
        }
        else {
            if (l_form.companyName.value == "") {
                alert("Trade company not entered");
                l_failed = true;
            }
        }

        if (checked == false) {
            alert("Record not selected");
            l_failed = true;
        }

        if (l_failed == true) {
            alert("Data not submitted due to one or more missing data entries!");
            _evt.preventDefault();
        }

    }


});
