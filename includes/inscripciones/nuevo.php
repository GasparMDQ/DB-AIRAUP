<?php
if (!isset($_POST['nuevo'])) {
	echo "Acceso Denegado";
} else {
	echo "&lt;NUEVO EVENTO&gt;";
?>

<form action="" method="post">
<table width="90%" border="0" align="center">
  <tr>
    <td width="150" align="left" valign="middle">Nombre:</td>
    <td align="left" valign="middle">&lt;texto&gt;</td>
    </tr>
  <tr>
    <td>Comienzo:</td>
    <td>&lt;dia&gt;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&lt;hora&gt;</td>
    </tr>
  <tr>
    <td>Fin:</td>
    <td>&lt;dia&gt;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&lt;hora&gt;</td>
    </tr>
  <tr>
    <td>Cierre de Inscripción:</td>
    <td>&lt;dia&gt;</td>
    </tr>
  <tr>
    <td>Distrito:</td>
    <td>&lt;lista distritos&gt;</td>
  </tr>
  <tr>
    <td>Club:</td>
    <td>&lt;lista clubes + ninguno (vacio)&gt;</td>
  </tr>
  <tr>
    <td>Coordinador:</td>
    <td>&lt;lista de socios del distrito o club segun corresponda&gt;</td>
  </tr>
  <tr>
    <td>Mail:</td>
    <td>&lt;si esta en blanco se usa el del perfil del coordinador mostrado en un label&gt; &lt;label&gt;</td>
  </tr>
  <tr>
    <td>Inscripciones:</td>
    <td>&lt;lista de socios del distrito o club segun corresponda (puede ser blanco, caso en el que se usan los datos del coordinador)&gt;</td>
  </tr>
  <tr>
    <td>Mail:</td>
    <td>&lt;si esta en blanco se usa el del perfil del coordinador mostrado en un label&gt; &lt;label&gt;</td>
  </tr>
  <tr>
    <td>Tesoreria:</td>
    <td>&lt;lista de socios del distrito o club segun corresponda (puede ser blanco, caso en el que se usan los datos del coordinador)&gt;</td>
  </tr>
  <tr>
    <td>Mail:</td>
    <td>&lt;si esta en blanco se usa el del perfil del coordinador mostrado en un label&gt; &lt;label&gt;</td>
  </tr>
  <tr>
    <td>Tipo de Encuentro:</td>
    <td>&lt;listado de tipos de encuentros&gt;</td>
  </tr>
  <tr>
    <td>Aprobación por Club:</td>
    <td>&lt;checkbox&gt;</td>
  </tr>
  <tr>
    <td>Aprobación por Distrito:</td>
    <td>&lt;checkbox&gt;</td>
  </tr>
  <tr>
    <td>Ticket:</td>
    <td>&lt;valor&gt;</td>
  </tr>
  <tr>
    <td>Cupo:</td>
    <td>&lt;numero entero&gt;</td>
  </tr>
  <tr>
    <td>Antiguedad de datos:</td>
    <td>&lt;preguntar siempre, intervalos de 15 dias hasta 60&gt;</td>
  </tr>
  <tr>
    <td>Nombre del Predio:</td>
    <td>&lt;texto&gt;</td>
  </tr>
  <tr>
    <td>Dirección del Predio:</td>
    <td>&lt;texto&gt;</td>
  </tr>
  <tr>
    <td>Forma de Pago:</td>
    <td>&lt;formas de pago&gt;</td>
  </tr>
  <tr>
    <td colspan="2">En caso de ser por depósito bancario completar:</td>
    </tr>
  <tr>
    <td>Nombre del Titular:</td>
    <td>&lt;texto&gt;</td>
  </tr>
  <tr>
    <td>Número de Cuenta:</td>
    <td>&lt;texto&gt;</td>
  </tr>
  <tr>
    <td>Sucursal:</td>
    <td>&lt;texto&gt;</td>
  </tr>
  <tr>
    <td>CBU:</td>
    <td>&lt;texto&gt;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>

<?php
}
?>