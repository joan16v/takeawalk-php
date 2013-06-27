            <? 
            
            if( isset($_SESSION['usuario']) ) {
                ?>
                <div style="text-align: right; margin-right:3px;"><b><? echo $_SESSION['usuario']; ?></b> [ <a href="close.php">Cerrar sesión</a> ] </div>
                <div style="margin-top:5px; text-align:right;">
                    <table align="right">
                        <tr><td><img src="images/flecha.png" /></td><td style="text-align: left;"><a style="color: #333;" class="extLink" href="/ajax/crear_ruta.php">Crear una ruta</a></td></tr>
                        <tr><td><img src="images/cuenta.png" /></td><td style="text-align: left;"><a style="color: #333;" class="extLink2" href="/ajax/mi_cuenta.php">Mi cuenta</a></td></tr>
                    </table>                    
                </div>
                <?            
            } else {
                ?>
                <form action="login.php" method="post">
                <table>
                <tr>
                  <td><div>correo electrónico</div>
                  <div><input type="text" name="email" /></div></td>
                  <td>
        		  <div>contraseña</div>
                  <div><input type="password" name="password" /></div>                  
                  </td>
                  <td valign="bottom"><div><input type="submit" value="iniciar sesión" /></div></td>
                  <td valign="bottom">
                    <table><tr><td><input type="checkbox" name="recordar" /></td><td>recordar</td></tr></table>
                  </td>
                </tr>
                </table>
                </form>   
                <div style="text-align:right">¿No tienes una cuenta? <a href="javascript:registrarse();" title="Regístrate">Regístrate</a></div>
                <?
            }
            
            ?>   