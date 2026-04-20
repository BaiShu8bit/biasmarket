const photocardsData = require("../data/photocards.json");

// obtener todas las photocards
const getAllPhotocards = (req, res) => {
    res.json(photocardsData.photocards);
};

// obtener photocard por ID
const getPhotocardById = (req, res) => {
    const id = req.params.id;

    const photocard = photocardsData.photocards.find(p => p.id === id);

    if (!photocard) {
        return res.status(404).json({ error: "Photocard no encontrada" });
    }

    res.json(photocard);
};

// filtrar por grupo
const getPhotocardsByGroup = (req, res) => {
    const groupId = req.params.groupId;

    const result = photocardsData.photocards.filter(
        p => p.group_id === groupId
    );

    res.json(result);
};

// filtrar por miembro
const getPhotocardsByMember = (req, res) => {
    const memberId = req.params.memberId;

    const result = photocardsData.photocards.filter(
        p => p.member_id === memberId
    );

    res.json(result);
};

// filtrar por álbum
const getPhotocardsByAlbum = (req, res) => {
    const albumId = req.params.albumId;

    const result = photocardsData.photocards.filter(
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