# BookingController
The controller is doing a lot of things. I would keep the crud methods for the job in this controller and have separate controllers for other actions like `accept`, `end` and `history` methods , in a may be `BookingUserController` and a `BookingCustomerController`. There is a concept of keeping your controllers `cruddy`, which I have found to be amazing and really helps you stick to the single responsibility principle. 

## Lack of Consistency
I like that there is a `BookingRepository` and operations are being delegated to it in some places, but it's not consistently done. Take the `distanceFeed` method for instance, this method should be in it's own controller first of all, secondly there are queries being done right from the controller, and that `response('Record updated!')` is also not consistent with the rest of responses being returned. I would just return a 200 success response from this, this `Record updated` doesn't communicate anything to the client.

## Error codes are successes
In the last method, in case of error, the key is set to `success` and no error code is being set, which makes the response have a 200 success code. There is also an unused variable `job_data` in this method.

## Lack of validation
The requests data is being assumed to be there, and no validation is being done at the controller level. 

## Unhandled error cases
Consider the method `getHistory`. it needs the `user_id` to be there, yet there is no validation being done, and if it's not there a simple `null` is being returned. 



# BookingRepository
At first glance I see a glaring violation of the single responsibility principle. This class is doing so much that it shouldn't be doing. It is manipulating jobs data, which it should do, but it is also sending notifications, sms, it is responsible for knowing if push should be sent or delayed, and converting hours to minutes etc. This is not ideal, and is going to create an app that is so badly coupled that it will become a nightmare to maintain.

## Literal Strings instead of `const`
There are a lot of places where application values like 'yes' or 'fail' or 'male' are being hardcoded every time, this is a bad design, these values should be put in their respective models or a global file for constants. Personally I have found a `App\Consts` file to be very practical for values such as this.

## Messages not in lang files
The response messages should be in the lang files so that later if localization is needed to be done, it is easier to maintain. 

## No use of database transactions
For methods where there are multiple tables being manipulated, these changes should be done within the context of a database transaction, and if something goes wrong that shouldn't the system should gracefully rollback the database, otherwise it would be a mess of dangling records that shouldn't be there and incomplete processes.

## Injecting the dependencies
First of all, this class should not be responsible for sending sms and push notifications, but given that it does, that logic should be encapsulated in it's own class, and that class should be injected into this as a dependency, and it should be injected as an interface so it could easily be swapped out by a different implementation.

## acceptJob and acceptJobWithId
These two methods are very confusing. This is a code smell that is indicating to me, that these are two methods that do the same thing but take different arguments, but looking at the code, there is siginificant difference between the two, but the names don't suggest that, which can be very problematic for a new comer.

## ajax method in repository
The method called `cancelJobAjax`. The repository class has no business knowing whether the request is coming from ajax or normally, and if the behaviour is supposed to be different, then it should be handled at the controller level. 

### Note
There is obviously a lot wrong with this. But to refactor all of it would take a very long time so I wouldn't dive into this. I have made some refactors to the accept job method in the controller and in the repository, and refactored some methods out to a separate controller. 
I have added test cases for the TeHelper class, and the `createOrUpdate` method in UserRepository is very problematic and to write a test for it, would require a refactor that will take a long time, So I am going to skip it as it is optional.
