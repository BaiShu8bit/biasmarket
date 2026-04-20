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

// photocard por ID
router.get("/:id", getPhotocardById);

// por grupo
router.get("/group/:groupId", getPhotocardsByGroup);

// por miembro
router.get("/member/:memberId", getPhotocardsByMember);

// por álbum
router.get("/album/:albumId", getPhotocardsByAlbum);

module.exports = router;