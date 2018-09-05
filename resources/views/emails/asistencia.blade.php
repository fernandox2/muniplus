<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Envio de Asistencia</title>
</head>
<body>
	<p>Estimado(a) <b>{{ $assistance->nombre }}:</b></p>
	<p>A continuación presentamos los registros de su asistencia el día <b>{{ $assistance->fecha }}</b></p>
	<hr>
	<p>Entrada 1: {{ $assistance->entrada1 }}</p>
	<p>Salida 1: {{ $assistance->salida1 }}</p>
	<p>Entrada 2: {{ $assistance->entrada2 }}</p>
	<p>Salida 2: {{ $assistance->salida2 }}</p>
	<hr>
	<p>Horas Trabajadas: <b>{{ $assistance->horas_trabajadas }}</b></p>
	<p>Comentario: {{ $assistance->comentario }}</p>
	<br>
	<p>Atte.</p>
	<p>--</p>
	<p><b>Equipo de Soporte</b></p>
	<br>
	<p><i><b>IMPORTANTE:</b> Este correo fue generado automáticamente, por favor no lo responda.</i></p>
	<p><i><b>IMPORTANTE:</b> Si usted tiene dudas sobre su asistencia diríjase al jefe de personal de su sección.</i></p>
</body>
</html>