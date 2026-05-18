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

// Search

exports.getGroups = (req, res) => {
    const search = req.query.search || "";
    const page = parseInt(req.query.page) || 1;
    const limit = 10;
    const offset = (page - 1) * limit;

    const sql = `
        SELECT * FROM grupos
        WHERE nombre LIKE ?
        LIMIT ? OFFSET ?
    `;

    const params = [`%${search}%`, limit, offset];

    db.query(sql, params, (err, results) => {
        if (err) return res.status(500).json(err);

        db.query(
            "SELECT COUNT(*) AS total FROM grupos WHERE nombre LIKE ?",
            [`%${search}%`],
            (err2, countResult) => {

                const total = countResult[0].total;

                res.json({
                    grupos: results,
                    page,
                    totalPages: Math.ceil(total / limit)
                });
            }
        );
    });
};