const fs = require("fs");
const path = require("path");

// ======================================
// LEER TODOS LOS JSON albums*
// ======================================

function cargarAlbums() {

    const dataFolder =
        path.join(__dirname, "../data");

    const files =
        fs.readdirSync(dataFolder);

    // solo albums*.json
    const albumFiles = files.filter(file =>

        file.startsWith("albums") &&
        file.endsWith(".json")
    );

    let allAlbums = [];

    albumFiles.forEach(file => {

        const filePath =
            path.join(dataFolder, file);

        const jsonData = JSON.parse(

            fs.readFileSync(filePath, "utf8")
        );

        // estructura:
        // { success: true, albums: [] }

        if (jsonData.albums) {

            allAlbums =
                allAlbums.concat(jsonData.albums);
        }
    });

    return allAlbums;
}

// ======================================
// TODAS + FILTRO OPCIONAL POR GROUP_ID
// ======================================

const getAllAlbums = (req, res) => {

    const albums = cargarAlbums();

    // 🔥 query string
    const groupId = req.query.groupId;

    // si viene groupId:
    // /api/albums?groupId=xxxxx

    if (groupId) {

const filtrados = albums.filter(

    album =>

        album.group &&
        album.group.id === groupId
);

        return res.json(filtrados);
    }

    // si no viene groupId -> todos
    res.json(albums);
};

// ======================================
// POR ID
// ======================================

const getAlbumById = (req, res) => {

    const albums = cargarAlbums();

    const album = albums.find(

        a => a.id === req.params.id
    );

    if (!album) {

        return res.status(404).json({

            error: "Álbum no encontrado"
        });
    }

    res.json(album);
};

// ======================================
// POR GRUPO
// ======================================

const getAlbumsByGroup = (req, res) => {

    const albums = cargarAlbums();

const result = albums.filter(

    a =>

        a.group &&
        a.group.id === req.params.groupId
);

    res.json(result);
};

// ======================================

module.exports = {

    getAllAlbums,
    getAlbumById,
    getAlbumsByGroup
};