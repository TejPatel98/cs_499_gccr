var removeInvalidClass = function(){
    this.classList.remove("is-invalid");
}

// Function to validate form on submit
var validate = function(event){
    var err = "";
    var alert = document.getElementById('errorMessage');
    var principle = this.principle;
    var investment = this.investmentPercent;
    var maxTradesPerDay = this.maxTradesPerDay;
    var minTradeLength = this.minTradeLength;
    var maxTradeLength = this.maxTradeLength;
    var start = this.startDate;
    var end = this.endDate;

    console.log(maxTradesPerDay.value + " " + minTradeLength.value + " " + maxTradeLength.value);

    if (principle.value == ""){
        err += "* Principle Amount required <br>";
        principle.classList.add('is-invalid');
        principle.addEventListener("change", removeInvalidClass);
    }
    if (investment.value == ""){
        err += "* Allocation Amount required <br>";
        investment.classList.add('is-invalid');
        investment.addEventListener("change", removeInvalidClass);
    }
    if (maxTradesPerDay.value == ""){
        err += "* Maximum Number of Trades per Day required <br>";
        maxTradesPerDay.classList.add('is-invalid');
        maxTradesPerDay.addEventListener("change", removeInvalidClass);
    }
    if (minTradeLength.value == ""){
        err += "* Minimum Trading Length required <br>";
        minTradeLength.classList.add('is-invalid');
        minTradeLength.addEventListener("change", removeInvalidClass);
    }
    if (maxTradeLength.value == ""){
        err += "* Maximum Trading Length required <br>";
        maxTradeLength.classList.add('is-invalid');
        maxTradeLength.addEventListener("change", removeInvalidClass);
    }
    if (minTradeLength.value >= maxTradeLength.value){
        err += "* Minimum Trading Length must be less than Maximum Trading Length <br>";
        maxTradeLength.classList.add('is-invalid');
        maxTradeLength.addEventListener("change", removeInvalidClass);
    }
    if (start.value >= end.value){	
        err += "* Start date must be before end date <br>";
        start.classList.add('is-invalid');
        start.addEventListener("change", removeInvalidClass);
    }

    if(err == "")
        return;
    
    alert.innerHTML = err;
    alert.style.display = "block";
    event.preventDefault();
}

var form = document.getElementById("portfolioSetup");
form.addEventListener("submit", validate, true);