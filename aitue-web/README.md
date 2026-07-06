# AITUE — Rediseño Web (Hero B)

Sitio estático. Sin dependencias ni build. Abrí `index.html` en el navegador o en VS Code con la extensión **Live Server**.

## Estructura
```
aitue-web/
├── index.html          ← página completa (una sola home)
├── css/
│   └── styles.css      ← todos los estilos
├── js/
│   └── main.js         ← nav mobile, hotspots, formulario, animaciones
└── assets/
    ├── img/            ← fotos, fondos e íconos de servicios
    └── icon/           ← íconos SVG de componentes + íconos de contacto
```

## Editar
- **Textos y links:** `index.html`.
- **Colores, tipos, espaciados:** variables al inicio de `css/styles.css` (`:root`).
- **Interacción (hotspots, form, menú):** `js/main.js`.

## Pendientes
- **Imágenes faltantes** de Industrias (marcadas "IMAGEN NECESARIA"): Minería, Agroindustria, Turismo, Emergencias, Seguridad, Prensa/AV y Profesionales — reemplazar el `src` del `<img>` en cada `.icell.ph` por la foto real (horizontal ~1200×800) y quitar la clase `ph` y el bloque `.need`.
- **Formulario:** hoy muestra confirmación visual. Para enviarlo de verdad, conectá el `submit` en `js/main.js` a tu backend (`contacto.php`, EmailJS, etc.).
- **WhatsApp:** reemplazá el número `5491162300000` en `index.html` por el real.
