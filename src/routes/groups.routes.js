const express = require("express");
const router = express.Router();

const {
    getAllGroups,
    getGroupById,
    searchGroups
} = require("../controllers/groups.controller");

// endpoints
router.get("/", getAllGroups);
router.get("/search", searchGroups);
router.get("/:id", getGroupById);

module.exports = router;