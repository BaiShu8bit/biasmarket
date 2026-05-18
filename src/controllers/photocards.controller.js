const fs = require("fs");
const path = require("path");

/*
========================================
CARGAR TODOS LOS JSON DE PHOTOCARDS
========================================
*/

function obtenerTodasLasPhotocards() {

    const dataFolder = path.join(__dirname, "../data");

    const files = fs.readdirSync(dataFolder);

    // SOLO archivos que empiecen por "photocards"
    const photocardFiles = files.filter(file =>
        file.startsWith("photocards") &&
        file.endsWith(".json")
    );

    let allPhotocards = [];

    photocardFiles.forEach(file => {

        const filePath = path.join(dataFolder, file);

        const jsonData = JSON.parse(
            fs.readFileSync(filePath, "utf8")
        );

        // Tu estructura:
        // { success: true, photocards: [] }

        if (jsonData.photocards) {

            allPhotocards =
                allPhotocards.concat(jsonData.photocards);
        }
    });

    return allPhotocards;
}

/*
========================================
OBTENER TODAS LAS PHOTOCARDS
========================================
*/

const getAllPhotocards = (req, res) => {

    const photocards = obtenerTodasLasPhotocards();

    res.json(photocards);
};

/*
========================================
OBTENER PHOTOCARD POR ID
========================================
*/

const getPhotocardById = (req, res) => {

    const id = req.params.id;

    const photocards = obtenerTodasLasPhotocards();

    const photocard = photocards.find(
        p => p.id === id
    );

    if (!photocard) {

        return res.status(404).json({
            error: "Photocard no encontrada"
        });
    }

    res.json(photocard);
};

/*
========================================
FILTRAR POR GRUPO
========================================
*/

const getPhotocardsByGroup = (req, res) => {

    const groupId = req.params.groupId;

    const photocards = obtenerTodasLasPhotocards();

    const result = photocards.filter(
        p => p.group_id === groupId
    );

    res.json(result);
};

/*
========================================
FILTRAR POR MIEMBRO
========================================
*/

const getPhotocardsByMember = (req, res) => {

    const memberId = req.params.memberId;

    const photocards = obtenerTodasLasPhotocards();

    const result = photocards.filter(
        p => p.member_id === memberId
    );

    res.json(result);
};

/*
========================================
FILTRAR POR ÁLBUM
========================================
*/

const getPhotocardsByAlbum = (req, res) => {

    const albumId = req.params.albumId;

    const photocards = obtenerTodasLasPhotocards();

    const result = photocards.filter(
        p => p.album_id === albumId
    );

    res.json(result);
};

module.exports = {
    getAllPhotocards,
    getPhotocardById,
    getPhotocardsByGroup,
    getPhotocardsByMember,
    getPhotocardsByAlbum
};