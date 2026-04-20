const membersData = require("../data/members.json");


// obtener miembros de un grupo (JSON completo del grupo)
const getMembersByGroup = (req, res) => {
    const groupId = req.params.groupId;

    const group = membersData.find(g => g.groupId === groupId);

    if (!group) {
        return res.status(404).json({ error: "Grupo no encontrado" });
    }

    // devolvemos el objeto completo del grupo
    res.json(group);
};

// obtener solo lista de miembros
const getOnlyMembers = (req, res) => {
    const groupId = req.params.groupId;

    const group = membersData.find(g => g.groupId === groupId);

    if (!group) {
        return res.status(404).json({ error: "Grupo no encontrado" });
    }

    res.json(group.members);
};

// obtener miembro por ID
const getMemberById = (req, res) => {
    const id = req.params.id;

    for (let group of membersData) {
        const member = group.members.find(m => m.id === id);

        if (member) return res.json(member);
    }

    res.status(404).json({ error: "Miembro no encontrado" });
};

module.exports = {
    getMembersByGroup,
    getOnlyMembers,
    getMemberById
};