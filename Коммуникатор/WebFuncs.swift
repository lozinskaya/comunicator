//
//  WebFuncs.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation

extension String {
 func getCleanedURL() -> URL? {
    guard self.isEmpty == false else {
        return nil
    }
    if let url = URL(string: self) {
        return url
    } else {
        if let urlEscapedString = self.addingPercentEncoding(withAllowedCharacters: CharacterSet.urlQueryAllowed) , let escapedURL = URL(string: urlEscapedString){
            return escapedURL
        }
    }
    return nil
 }
}

class WebFuncs {
    static func convertStringToDictionary(text: String) -> [String:AnyObject]? {
        if let data = text.data(using: .utf8) {
            do {
                let json = try JSONSerialization.jsonObject(with: data, options: .mutableContainers) as? [String: AnyObject]
                return json
            } catch {
                print("JSON parse error :(")
            }
        }
        return nil
    }
    
    enum Answer: String {
        case EMPTY_FIELDS = "0"
        case UNKNOWN_ERROR = "1"
        case SUCCESS = "2"
        case REPEAT_STRING = "3"
        case WRONG_USER = "4"
        case NO_REPEAT_PASS = "5"
    }
    
    struct JSONAnswer : Decodable {
        let result: String?
        let success: Bool?
    }
    
    static let action_url = "https://cafe.paulislava.space/app/request.php";
    
    static func ActionUrl(action: String, params: [String: String]) -> URL {
        var urlPath = action_url + "?action=" + action;

        for (key, val) in params {
            urlPath = urlPath + "&" + key + "=" + val;
        }
        print("Request url: " + urlPath);
        
        
        let url = urlPath.getCleanedURL()!;
        return url;
    }
    
    static func Request(action: String, params: [String: String], completion: @escaping (String?)->()) {
        let url = ActionUrl(action: action, params: params);
        
        URLSession.shared.dataTask(with: url as URL) { data, response, error in
          if error != nil{
            print("Request error.");
            return completion("")
          }else{
            let result = String(decoding: data! as Data, as: UTF8.self);
            print("Data returned: " + result);
            return completion(result);
          }
      }.resume()
    }
    
    static func JSONRequest(action: String, params: [String: String], completion: @escaping ([String:AnyObject]?)->()) {
        Request(action: action, params: params) { data in
            if let result = data {
                print(result);
                let resultJSON = convertStringToDictionary(text: result)
                completion(resultJSON);
            }
        }
    }
    
    static func Login(email: String, pass: String, completion: @escaping (Bool) ->()) {
        JSONRequest(action: "login", params: ["email": email, "pass": pass]) { data in
            
            if let result = data {
                if result["result"] as? String == WebFuncs.Answer.SUCCESS.rawValue {
                    Global.sessionkey = result["session_key"] as! String;
                    Global.userinfo = result["info"] as! [String : AnyObject];
                    Global.balance = Global.userinfo["balance"] as? String ?? "0";
                    completion(true);
                } else {
                    completion(false);
                }
            }
        }
    }
    
    static func Reg(params: [String: String], completion: @escaping ([String:AnyObject]?) ->()) {
        JSONRequest(action: "reg", params: params) { data in
            if let result = data {
                print(result);
                completion(result);
            }
        }
    }
}
