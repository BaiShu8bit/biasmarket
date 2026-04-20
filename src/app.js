const express = require("express");
const app = express();

const groupsRoutes = require("./routes/groups.routes");

app.use(express.json());

// rutas
app.use("/api/groups", groupsRoutes);

module.exports = app;