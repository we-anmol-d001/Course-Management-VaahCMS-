
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Confirmation</title>
</head>
<body>
    <h2>Welcome, {{ $student->name }}!</h2>
    <p>We are excited to inform that you are successfully registered.</p>
    @if($student->courses)
    <p>You have been enrolled in following courses:</p>
    <ul>
        @foreach($student->courses as $course)
            
            <li>{{ $course->name }}</li>
        @endforeach
    </ul>
    @else
        <p>You have not enrolled in any course yet.</p>
        <p>complete further processing.</p>
       
    @endif
     <p>Thank You</p>
</body>
</html>
