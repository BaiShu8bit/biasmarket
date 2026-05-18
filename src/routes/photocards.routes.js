const express = require("express");
const router = express.Router();

const {
    getAllPhotocards,
    getPhotocardById,
    getPhotocardsByGroup,
    getPhotocardsByMember,
    getPhotocardsByAlbum
} = require("../controllers/photocards.controller");

// todas las photocards
router.get("/", getAllPhotocards);

// por grupo
router.get("/group/:groupId", getPhotocardsByGroup);

// por miembro
router.get("/member/:memberId", getPhotocardsByMember);

// por álbum
router.get("/album/:albumId", getPhotocardsByAlbum);

// photocard por ID (SIEMPRE AL FINAL)
router.get("/:id", getPhotocardById);

module.exports = router;