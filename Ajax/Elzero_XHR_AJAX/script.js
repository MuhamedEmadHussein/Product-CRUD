function getData() {
    let myRequest = new XMLHttpRequest();

    myRequest.onreadystatechange = function () {
        /*
        Ready State: The state of the request
        readyState Values:
        0: Uninitialized => Request not initialized
        1: Loading => Server connection established
        2: Loaded => Request received
        3: Interactive => Processing request
        4: Completed => Request finished and response is ready
        */
        // When Request finished and response is ready and status code is 200 (ok)
        // Get The Response Text
        if (this.readyState === 4 && this.status === 200) {
            // console.log(this.responseText);

            let jsObj = JSON.parse(this.responseText),
                myText = '';

            for(let i=0; i< jsObj.length; i++){
                console.log(jsObj[i]);   
            }

        }
    }

    // XMLHttpRequest.open(Method, URL, Async    
    myRequest.open("GET", "https://api.github.com/users/ElzeroWebSchool/repos");
    myRequest.setRequestHeader('Content-Type','application/json');
    myRequest.send();
}

getData();


