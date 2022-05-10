/*

Give the service worker access to Firebase Messaging.

Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.

*/

importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');

importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

   

/*

Initialize the Firebase app in the service worker by passing in the messagingSenderId.

* New configuration for app@pulseservice.com

*/

firebase.initializeApp({

    apiKey: "AIzaSyAaJvTq9pmWbw8UX0P_oxGxGDhM5tlzUWc",

    authDomain: "laravel-push-82ee3.firebaseapp.com",
  
    projectId: "laravel-push-82ee3",
  
    storageBucket: "laravel-push-82ee3.appspot.com",
  
    messagingSenderId: "1002058481533",
  
    appId: "1:1002058481533:web:673dca4bf12182a066d614",
  
    measurementId: "G-Q6KF06RDL7"
  

    });

  

/*

Retrieve an instance of Firebase Messaging so that it can handle background messages.

*/

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {

    console.log(

        "[firebase-messaging-sw.js] Received background message ",

        payload,

    );

    /* Customize notification here */

    const notificationTitle = "Background Message Title";

    const notificationOptions = {

        body: "Background Message body.",

        icon: "/itwonders-web-logo.png",

    };

  

    return self.registration.showNotification(

        notificationTitle,

        notificationOptions,

    );

});