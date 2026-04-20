const express = require("express");
const router = express.Router();

const {
    getAllAlbums,
    getAlbumById,
    getAlbumsByGroup
} = require("../controllers/albums.controller");

// todos los albums
router.get("/", getAllAlbums);

// albums por grupo
router.get("/group/:groupId", getAlbumsByGroup);

// album por ID
router.get("/:id", getAlbumById);

module.exports = router;