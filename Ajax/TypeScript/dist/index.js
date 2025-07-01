"use strict";
// const username = 'mohamed' -> Type Inference
// Type Annotation for API Response 
// Type Annotation
const apiResponse = {
    id: 1,
    name: "John Doe",
    email: "john.doe@example.com",
    phone: "+1234567890",
    address: {
        street: "123 Main St",
        city: "Anytown",
        state: "CA",
        zip: "12345"
    }
};
const userData = apiResponse;
console.log(userData);
