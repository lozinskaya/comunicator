//
//  Global.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 19.12.2020.
//

import Foundation

class Global {
    static var user_id: String = ""
    static var userinfo: [String: AnyObject] = [:]
    static var sessioninfo: [String: AnyObject] = [:]
    static var activesession: [String: AnyObject] = [:]
    static var lastsession: [String: String] = [:]
    static var is_active: Int = 0
    static var balance : String = "0"
    static var sessionkey: String = ""
}
