const albumsData = require("../data/albums.json");

// obtener todos los albums
const getAllAlbums = (req, res) => {
    res.json(albumsData.albums);
};

// obtener album por ID
const getAlbumById = (req, res) => {
    const id = req.params.id;

    const album = albumsData.albums.find(a => a.id === id);

    if (!album) {
        return res.status(404).json({ error: "Album no encontrado" });
    }

    res.json(album);
};

// obtener albums por grupo
const getAlbumsByGroup = (req, res) => {
    const groupId = req.params.groupId;

    const albums = albumsData.albums.filter(
        a => a.group.id === groupId
    );

    if (albums.length === 0) {
        return res.status(404).json({ error: "No hay albums para este grupo" });
    }

    res.json(albums);
};

module.exports = {
    getAllAlbums,
    getAlbumById,
    getAlbumsByGroup
};