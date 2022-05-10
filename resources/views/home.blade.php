@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center my-3">
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Almacenar Token</button>
            </div>
            <div class="card">
                <div class="card-header">Enviar Notificación Push</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('notification') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label>Título</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group mb-2">
                            <label>Descripción</label>
                            <textarea class="form-control" name="body"></textarea>
                          </div>
                        <button type="submit" class="btn btn-primary float-end">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyAaJvTq9pmWbw8UX0P_oxGxGDhM5tlzUWc",
        authDomain: "laravel-push-82ee3.firebaseapp.com",
        projectId: "laravel-push-82ee3",
        storageBucket: "laravel-push-82ee3.appspot.com",
        messagingSenderId: "1002058481533",
        appId: "1:1002058481533:web:673dca4bf12182a066d614",
        measurementId: "G-Q6KF06RDL7"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
            messaging.requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: '{{ route("token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert(response[0]);
                    },
                    error: function (err) {
                        alert('Error: '+ err);
                    },
                });
            }).catch(function (err) {
                alert('Error: '+ err);
            });
     }  

    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });
</script>

@endsection