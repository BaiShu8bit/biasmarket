const express = require("express");
const router = express.Router();

const {
    getMembersByGroup,
    getOnlyMembers,
    getMemberById
} = require("../controllers/members.controller");

// JSON completo del grupo
router.get("/group/:groupId/full", getMembersByGroup);

// solo miembros
router.get("/group/:groupId", getOnlyMembers);

// miembro individual
router.get("/member/:id", getMemberById);

module.exports = router;