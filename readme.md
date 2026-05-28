<div align="center">

![Biasmarket Logo and website](./contenido/iconos/RM-logo.png)

</div>

## Información

Este repositorio contine información sobre biasmarket website:

- API desarrollada con [Next.js](https://nodejs.org/es)
- Utilización de [Bootstrap](https://getbootstrap.com/) como framework CSS

## Modo Local

1. Haz un fork del repositorio haciendo clic en la opción Fork en la parte superior derecha del repositorio principal.

2. Instala la BBDD tfg.sql proporcionada dentro de la carpeta biasmarket.

3. Abre el Símbolo del sistema (Command Prompt) en tu ordenador.

4. Clona el repositorio forkeado sustituyendo <username> por tu propio nombre de usuario de GitHub.


```bash
    git clone https://github.com/<username>/website/
```

5. Mueve el repositorio dentro de httdocs (si se usa Xammp)

6. Navega hasta el directorio del sitio web desde el terminal o su IDE.

```bash
    cd biasmarket
```

7. Ejecuta el sitio web localmente.

```bash
    npm run dev
```

## Introducción a biasmarket

<div align="center">

![website](./contenido/iconos/RM-web.png)

</div>

biasmarket es una website de compra-venta de photocards. Los usuario podrán navegar por la web para ver las distintas photocards de idols, sus álbumes, así como todos los grupos de k-pop existentes hasta el momento.

### Usuario (admin)

Si se desea acceder como usuario (admin), se debe utilizar las siguientes credenciales:

| **Usuario** | **Contraseña** |
| :--- | :--- |
| baishuo | admin1234 |

### Funcionalidades

Dentro de biasmarket, se puede hacer lo siguiente:

- Registrarse como usuario.
- Vender / Comprar photocards (es obligatorio registro previo).
- Navegar por las distintas páginas de cada grupo.

### API

El script AJAX no realiza una petición a un servicio externo de terceros, sino que apunta directamente a un endpoint propio de nuestra aplicación.

Esta API interna, que he diseñado y programado yo mismo en el servidor, es la encargada de procesar la solicitud de AJAX, consultar la base de datos, estructurar la información en formato JSON y devolverla al cliente para que AJAX la inyecte en la página web sin necesidad de recargar el navegador.

> [!NOTE]
> Debido a que las imágenes de las photocards no están guardadas localmente, sino ubicadas en la nube, existe la posibilidad de que algunas imágenes no sean visibles.

### ❗Importante

Tener en cuenta que debido a la gran cantidad de grupos e idols existentes, se ha limitado la API a unos pocos grupos y sus integrantes. Dichos grupos son:

- KISS OF LIFE
- i-dle
- LE SSERAFIM
- BLACKPINK
- ITZY

### Buscador

<div align="center">

![Buscador](./contenido/iconos/RM-buscador.png)

</div>

Se pueden buscar photocards utilizando el buscador. Una photocard tiene el siguiente orden:

🟢 `ITZY - Chaeryeong - Motto Album album photocard`

Se recomienda buscar por nombre de idol o grupo. Si se desea ambas, se debe escribir en el orden mostrado en el ejemplo.

### Secciones

* **Las más destcadas:** Aparecerán las *photocards* más destacadas.
> [!NOTE]
> Al ser una website nueva, se ha utilizado un random para la visualización de photocards.

* **Albums:** Se accede a las *photocards* de las *idols* correspondientes al álbum seleccionado.
* **Grupos:** Lleva a todos los álbumes del grupo seleccionado.


Una vez dentro de un album, al pulsar sobre la photocard que se quiere, se abrirá una sección donde se podrá poner a la venta o comprar (si estuviera listada) la photocards.

### Vender photocards.

Se pueden añadir photocards a la venta. Para ello es tan simple como darle al botón de Vender, y completar la información que se le pide.


### Comprar photocards

Una photocard listada se puede añadir al carrito haciendo click sobre el icono de carrito. En caso de haber más copias de la misma, se puede seleccionar la cantidad a añadir.

### Carrito (dentro de cabecera).

Una vez añadida la photocard, esta aparecerá dentro del carrito. Para comprarla solo hay que seguir los pasos.

### Mis compras

Una vez realizada la compra, podrás verla listada dentro de COMPRAR > MIS COMPRAS. También podrás descargarla en .pdf

### Mis ventas

Se puede ver un registro de las ventas que has realizado como usuario. Estás estarán listadas dentro de VENDER > MIS VENTAS (tener en cuenta que solo se mostrarán las ventas realizadas por el usuario actual).
