    <form action="{{url('/')}}/register/{{$id}}" id="RegForm" method="POST">
        <div>
            <label for="name">name : </label>
            <input type="text" name="name" id="name">
        </div>
        <div>
            <label for="email">email : </label>
            <input type="text" name="email" id="email">
        </div>
        <div>
            <label for="password">password : </label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="confirmPassword">confirm Password : </label>
            <input type="password" name="password_confirmation" id="confirm_password">
        </div>
        <div>
            <label for="phone">phone : </label>
            <input type="text" name="phone" id="phone">
        </div>
        <div>
            <label for="address">address : </label>
            <input type="text" name="address" id="address">
        </div>
        <button type="submit">submit</button>
    </form>