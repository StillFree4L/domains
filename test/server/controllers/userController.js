const ApiError = require('../error/ApiError')
const bcrypt = require('bcrypt')
const jwt = require('jsonwebtoken')
const {Balance} = require('../models/models')

const generateJwt =(id,email,role) =>{
	return jwt.sign({id:user.id,email,role},process.env.SECRET_KEY,{expiresIn: '24h'})
}

class UserController {
	async registration(req,res,next){
		const {email,password,role}=req.body
		if (!email && !password) {
			return next(ApiError.badRequest('Некоректная почта или пароль'))
		}
		const candidate = await User.findOne({where:{email}})
		if(candidate){
			return next(ApiError.badRequest('Пользователь существует'))
		}
		const hashPassword = await bcrypt.hash(password,5)
		const user = await User.create({email,role,password:hashPassword})
		const balance = await Balance.create({userId:user.id,balance:0})
		const token = generateJwt(userId,email,role)
		return res.json({token})
	}

	async login(req,res,next){
		const {email,password}=req.body
		const candidate = await User.findOne({where:{email}})
		if(!candidate){
			return next(ApiError.badRequest('Пользователь с не существует'))
		}
		let comparePassword = bcrypt.compareSync(password,user.password)
		if(!comparePassword){
			return next(ApiError.badRequest('Указан не верный пароль'))
		}
		const token = generateJwt(user.id,user.email,user.role)
		return res.json({token})
	}

	async check(req,res){
		const token = generateJwt(req.user.id,req.user.email,req.user.role)
		return res.json({token})
	}
}

module.exports=new UserController()