<div align="center">

![Biasmarket Logo and website](./contenido/iconos/RM-logo.png)

</div>
---
## Información

Este repositorio contine información sobre biasmarket website:

- API desarrollada con [Next.js](https://nodejs.org/es)
- Utilización de [Bootstrap](https://getbootstrap.com/) como framework CSS

## Modo Local

Haz un fork del repositorio haciendo clic en la opción Fork en la parte superior derecha del repositorio principal.

Instala la BBDD tfg.sql proporcionada dentro de la carpeta biasmarket.

Abre el Símbolo del sistema (Command Prompt) en tu ordenador.

Clona el repositorio forkeado sustituyendo <username> por tu propio nombre de usuario de GitHub.


```bash
    git clone https://github.com/<username>/website/
```

Mueve el repositorio dentro de httdocs (si se usa Xammp)

6. Navega hasta el directorio del sitio web desde el terminal o su IDE.

```bash
    cd biasmarket
```

7. Ejecuta el sitio web localmente.

```bash
    npm run dev
```

## Introducción a biasmarket


IMAGEN

biasmarket es una website de compra-venta de photocards. Los usuario podrán navegar por la web para ver las distintas photocards de idols, sus álbumes, así como todos los grupos de k-pop existentes hasta el momento.

### Funcionalidades

Dentro de biasmarket, se puede hacer lo siguiente:


- Registrarse como usuario.
- Vender / Comprar photocards (es obligatorio registro previo).
- Navegar por las distintas páginas de cada grupo.

### API

Se ha creado una API con las photocards, álbumes y grupos para que se pueda ver en la website.

Debido a que las imágenes de las photocards no están guardadas localmente, sino ubicadas en la nube, existe la posibilidad de que algunas imágenes no aparezcan.

### Importante

Tener en cuenta que debido a la gran cantidad de grupos e idols existentes, se ha limitado la API a unos pocos grupos y sus integrantes. Dichos grupos son:

KISS OF LIFE
i-dle
LE SSERAFIM
BLACKPINK
ITZY

### Buscador

<div align="center">

![Buscador](./contenido/iconos/RM-buscador.png)

</div>

Se pueden buscar photocards utilizando el buscador. Una photocard tiene el siguiente orden:

<span style="color: green; font-size: calc(1rem - 2px);">ITZY - Chaeryeong - Motto Album album photocard</span>


Se recomienda buscar por nombre de idol o grupo. Si se desea ambas, se debe escribir en el orden mostrado en el ejemplo.

### Secciones

* **Las más vendidas:** Aparecerán las *photocards* más vendidas.
> ##### Nota: Al ser una website nueva, se ha utilizado un random para la visualización de photocards.

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
