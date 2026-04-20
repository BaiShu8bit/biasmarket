const groupsData = require("../data/groups.json");

// obtener todos
const getAllGroups = (req, res) => {
    res.json(groupsData[0].data.groups);
};

// buscar por nombre
const searchGroups = (req, res) => {
    const name = req.query.name?.toLowerCase();

    const results = groupsData[0].data.groups.filter(group =>
        group.name.toLowerCase().includes(name)
    );

    res.json(results);
};

// obtener por ID
const getGroupById = (req, res) => {
    const id = req.params.id;

    const group = groupsData[0].data.groups.find(g => g.id === id);

    if (!group) {
        return res.status(404).json({ error: "Grupo no encontrado" });
    }

    res.json(group);
};

module.exports = {
    getAllGroups,
    getGroupById,
    searchGroups
};