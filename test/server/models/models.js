const sequelize=require('../db')
const {DataTypes}=require('sequelize')

const User=sequelize.define('user',{
	id:{type: DataTypes.INTEGER,primaryKey:true,autoIncrement:true},
	email:{type: DataTypes.STRING,unique:true},
	password:{type: DataTypes.STRING},
	role:{type: DataTypes.STRING,defaultValue:"USER"},
})

const Balance=sequelize.define('balance',{
	id:{type: DataTypes.INTEGER,primaryKey:true,autoIncrement:true},
	balance:{type: DataTypes.INTEGER},
})

User.hasMany(Balance)
Balance.belongsTo(User)

module.exports={
	User,
	Balance
}